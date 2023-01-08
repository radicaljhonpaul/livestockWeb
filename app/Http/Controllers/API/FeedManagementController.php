<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Response;
use DateInterval;
use DateTime;


// Models
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\SrpYear;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\FeedingLog;
use App\Models\FeedingLogIngredient;
use App\Models\NutrientDetail;
use App\Models\AdminLevelThree;
use Illuminate\Support\Facades\Config;


class FeedManagementController extends Controller
{

    public function listBySrp(Request $request){
        
        $srp_id =  $request->srp_id;

        return Category::with(['ingredients' => function($query) use ($srp_id){
            return $query->join('ingredient_srp_year', 'ingredient_srp_year.ingredient_id', '=', 'ingredients.id')
                     ->where('ingredient_srp_year.srp_year_id', $srp_id)->get();
            }])
        ->orderBy('display_order')
        ->get();

      /* Getting at ingredient level only 
         Ingredient::join('ingredient_srp_year', 'ingredient_srp_year.ingredient_id', '=', 'ingredients.id')
                        ->where('ingredient_srp_year.srp_year_id', $srp_id)
                        ->orderBy('ingredients.category_id','DESC')
                        ->get(['ingredients.*', 'ingredient_srp_year.price']);*/


    }


    public function srpyears(Request $request){

        return SrpYear::all();

    }

    /**
     * PCCP2-399
     * Get Feeding Logs Based on User's Admin Level Two Association
     */
    public function getFeedingLogsAssigned(Request $request){
  
        $user =  $request->user();

        $dateFrom = new DateTime($request->syncFromDate);
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $dateToAdd1Day = $dateTo->format('Y-m-d');


        $feedingLogs = FeedingLog::whereHas('livestock', function ($query) use ($user) {
                            $query->whereHas('farmer', function($query) use ($user) {
                                $query->whereHas('adminLevelThree', function($query) use ($user){
                                    $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                                });
                            });
                        })
                        ->whereBetween('updated_at',[$dateFrom, $dateToAdd1Day])
                        ->with('feedingLogIngredients')
                        ->with('nutrientDetails')
                        ->with('livestock:id,carabao_code,farmer_id')
                        ->get();

        return Response::json(array(
                'feedingLogs' => $feedingLogs
        ));

    }


    public function getFeedingLogsAssignedPaginated(Request $request){
  
        $user =  $request->user();

        $dateFrom = new DateTime($request->syncFromDate);
        $dateTo = new DateTime($request->syncToDate);
        $dateTo->add(new DateInterval('P1D'));
        $dateToAdd1Day = $dateTo->format('Y-m-d');


        $feedingLogs = FeedingLog::whereHas('livestock', function ($query) use ($user) {
                            $query->whereHas('farmer', function($query) use ($user) {
                                $query->whereHas('adminLevelThree', function($query) use ($user){
                                    $query->whereIn('admin_level_two_id', $user->adminLevelTwos->pluck('id'));
                                });
                            });
                        })
                        ->whereBetween('updated_at',[$dateFrom, $dateToAdd1Day])
                        ->with('feedingLogIngredients')
                        ->with('nutrientDetails')
                        ->with('livestock:id,carabao_code,farmer_id')
                        ->simplePaginate(50)
                        ->setPath(Config::get('app.url').$request->path());    //for prod ip to url handling

        return Response::json(array(
                'feedingLogs' => $feedingLogs
        ));

    }


    /**
     * PCCP2-423 - Sync Up Feeding Logs
     */
    public function syncUpFeedingLogs(Request $request){

        //ob_start('ob_gzhandler');
     
        $feedingLogExtIdArr = array();
        $tempLactationStage = null;
        $isLactating = null;  

            if($request->has('feedingLogs')){   
                
                DB::beginTransaction();
                
                try{

                    foreach($request->feedingLogs as $feedingLog){

                        $feedingLogExtIdArr[] = $feedingLog['external_id'];
                        
                        $lactationStage = $feedingLog['lactation_stage']  ?? null;

                        if( $lactationStage != null &&  $lactationStage != ''){
                            $isLactating = 1;
                        }
                        else{
                            $isLactating = 0;
                        }

                        FeedingLog::updateOrInsert(
                        [ 
                                'external_id' => $feedingLog['external_id'],
                        ],
                        [
                                'visit_date' => $feedingLog['visit_date'],
                                'body_weight' => $feedingLog['body_weight'],
                                'category' => $feedingLog['category'],
                                'is_lactating' => $isLactating  ?? null,
                                'lactation_stage' => $lactationStage,
                                'is_pregnant' => $feedingLog['is_pregnant']  ?? null,
                                'ave_daily_gain' =>  $feedingLog['ave_daily_gain']  ?? null,
                                'milk_yield_per_day' => $feedingLog['milk_yield_per_day']  ?? 0,
                                'milk_price' => $feedingLog['milk_price']  ?? null,
                                'total_cost_per_day' => $feedingLog['total_cost_per_day']  ?? 0,
                                'feed_cost_per_kg' => $feedingLog['feed_cost_per_kg'] ?? 0,
                                'income' => $feedingLog['income']  ?? 0,
                                'livestock_id' => $feedingLog['livestock_id'] ?? 1,
                                'created_by' => $feedingLog['created_by'],
                                'pregnancy_month' => $feedingLog['pregnancy_month']  ?? null,
                                'fat_protein' => $feedingLog['fat_protein']  ?? null,
                                'ration_name' => $feedingLog['ration_name']  ?? null,
                                'total_as_fed_kg' => $feedingLog['total_as_fed_kg']  ?? null,
                                'srp_year_id' => $feedingLog['srp_year_id'],
                                'updated_at' => Carbon::now(),
                        ]
                        );

                    }

                    //Get Parent id of newly inserted / updated & delete child records
                    $feedingLogIds = FeedingLog::select('id', 'external_id')->whereIn('external_id', $feedingLogExtIdArr)->get();
                    FeedingLogIngredient::whereIn('feeding_log_id', $feedingLogIds->pluck('id') )->delete();
                    NutrientDetail::whereIn('feeding_log_id', $feedingLogIds->pluck('id') )->delete();

                    //Insert child records - feeding log ingredient
                    foreach($request->feedingLogs as $feedingLog){
                        
                        $tempParentId = null;
                       
                        //loop through list to find the id matching the external id
                        for($i=0; $i < count($feedingLogIds) ; $i++){
                            
                            if( $feedingLogIds[$i]->external_id ==  $feedingLog['external_id']){
                                $tempParentId = $feedingLogIds[$i]->id;
                            }

                        }

                        $tempFeedLogIngredientsList = $feedingLog['feeding_log_ingredients'] ?? null;

                        if($tempFeedLogIngredientsList != null){

                            foreach($tempFeedLogIngredientsList as $feedingLogIngredient){

                                FeedingLogIngredient::create(
                                    [
                                        'feeding_log_id' => $tempParentId,
                                        'ingredient_id' => $feedingLogIngredient['ingredient_id'],
                                        'quantity' => $feedingLogIngredient['quantity'],
                                        'price_at_date' => $feedingLogIngredient['price_at_date'],
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
            
                                    ]
                                );
                            }
                            
                        }

                    
                    
                        $tempNutrientDetailsList = $feedingLog['nutrient_details'] ?? null;
                    
                        if( $tempNutrientDetailsList != null){

                            foreach($tempNutrientDetailsList as $nutrientDetail){

                                NutrientDetail::create(
                                    [
                                        'feeding_log_id' => $tempParentId,
                                        'type' => $nutrientDetail['type'],
                                        'dm' => $nutrientDetail['dm'],
                                        'me' => $nutrientDetail['me'],
                                        'cp' => $nutrientDetail['cp'],
                                        'ndf' => $nutrientDetail['ndf'],
                                        'tdn' => $nutrientDetail['tdn'],
                                        'ca' => $nutrientDetail['ca'],
                                        'p' => $nutrientDetail['p'],
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ]
                                );

                            }

                        }

                    }

                DB::commit();

                //ob_end_flush();
                return response(['message' => "Synched Up successfully ".sizeof($request->feedingLogs)." record(s)"], 200);
    

                }catch (\Exception $e) {
                    DB::rollback();
                    return response(['message' => $e->getMessage()], 401);
                }
            

        }

        //ob_end_flush();
        return response(['message' => "No feedingLogs detected. 0 record updated."], 200);

    }


    /**
     * Handles the if field is either not existing, -1 or null = returns as null
     * If valid value, return value
     */
    private function cleanRequestValue($feedingLog, $field){

        $defaultValue = $feedingLog[ $field ] ?? null;

        if($defaultValue == null || $defaultValue == "-1"){
            return null;
        }

        return $defaultValue;
    }

}