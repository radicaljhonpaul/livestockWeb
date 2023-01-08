<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

// Models
use App\Models\Farmer;
use App\Models\AdminLevelOne;
use App\Models\AdminLevelTwo;
use App\Models\AdminLevelThree;
use App\Models\Livestock;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class FarmerController extends Controller
{

    /**
     * Gets all the admin level locations based on the assigned location (user_admin_level_two) table
     * Gets all farmers and livestock based on assigned location (user_admin_level_two) table
     * 
     * This function expects that there is a logged in user, and has at least 1 admin_level_two (city/municipality) assigned to that user
     * all the admin_level_threes under the admin_level_two where user is assigned will be used to filter farmer & livestock record
     * 
     * Returns two set of data:
     *      farmers => farmers & livestock
     *      admin_level_ones => filtered information from level one (province) > level two (city / munitipality) > level three (baranggay)
     * 
     * TIP: Returns many farmers
     */
    public function getFarmersAssigned(Request $request){

           
        $adminLevelOnesScope = $request->user()->adminLevelTwos->pluck('admin_level_one_id');

        $locationScope = AdminLevelOne::with(['adminLevelTwos' => function($query) use ($request){
                            return $query->with('adminLevelThrees')
                                        ->get();
                            }])
                        ->whereIn('id', $adminLevelOnesScope)
                        ->get();
        

        $adminLevelThreeScope = AdminLevelThree::whereIn('admin_level_two_id', $request->user()->adminLevelTwos->pluck('id'))
                               ->get()->pluck('id');

    
        $farmerScope = Farmer::with(['livestocks' => function($query) use ($request){
                    return $query->with('pregnancies')
                                ->get();
                    }])
                ->whereIn('admin_level_three_id', $adminLevelThreeScope)
                ->get();

        return Response::json(array(
                    'admin_level_ones' => $locationScope,
                    'farmers' => $farmerScope
                ));

    }

    public function getAdminLevelsAssignedPaginated(Request $request){

        $adminLevelOnesScope = $request->user()->adminLevelTwos->pluck('admin_level_one_id');

        $locationScope = AdminLevelOne::with(['adminLevelTwos' => function($query) use ($request){
                            return $query->with('adminLevelThrees')
                                        ->get();
                            }])
                        ->whereIn('id', $adminLevelOnesScope)
                        ->simplePaginate(50)
                        ->setPath(Config::get('app.url').$request->path());    //for prod ip to url handling


        return Response::json(array(
                    'admin_level_ones' => $locationScope
        ));

    }


    public function getFarmersAssignedPaginated(Request $request){


        $adminLevelThreeScope = AdminLevelThree::whereIn('admin_level_two_id', $request->user()->adminLevelTwos->pluck('id'))
                               ->get()->pluck('id');

    
        $farmerScope = Farmer::with(['livestocks' => function($query) use ($request){
                    return $query->with('pregnancies')
                                ->get();
                    }])
                ->whereIn('admin_level_three_id', $adminLevelThreeScope)
                ->simplePaginate(50)
                ->setPath(Config::get('app.url').$request->path());    //for prod ip to url handling

        return Response::json(array(
                    'farmers' => $farmerScope
                ));

    }   


    /**
     * Gets the specific farmer and livestock data based on parameters provided
     * An additional filter indicating location scope of logged in user is added for 
     * 
     * Params: farmer_id = id of farmer in livestockweb database
     *         module = additional information related to farmer and livestock is included depending on module
     *         - if module = diagnosis -> include livestock pregnancies, diagnosis_logs, diagnosis_logs symptoms, health_condition and interventions   
     *         - TODO: if module = feeds -> include feeding_reco_log
     *
     * TIP: Returns 1 farmer
     */
    public function getSpecificFarmerAssigned(Request $request){

        $farmer_id =  $request->farmer_id;
        $module = $request->module;

        //ensure admin scope level is still checked with retrieval of farmer record
        $adminLevelThreeScope = AdminLevelThree::whereIn('admin_level_two_id', $request->user()->adminLevelTwos->pluck('id'))
                      ->get()->pluck('id');

        if($module == "diagnosis"){
            $farmerScope = Farmer::with(['livestocks' => function($query) use ($farmer_id){
                            return $query->with('diagnosisLogs', 'pregnancies')
                                         ->with('diagnosisLogs.symptoms:id')
                                         ->with('diagnosisLogs.healthConditions:id')
                                         ->with('diagnosisLogs.interventions:id')
                                         ->get();
                            }])
                        ->whereIn('admin_level_three_id', $adminLevelThreeScope)
                        ->where('id', $farmer_id)
                        ->get();

        }
        
        //TODO: Add module for feeds w/ recommendation information
        
        else {

            $farmerScope = Farmer::with(['livestocks' => function($query) use ($request){
                    return $query->with('pregnancies')
                                ->get();
                    }])
                    ->whereIn('admin_level_three_id', $adminLevelThreeScope)
                    ->where('id', $farmer_id)
                    ->get();


        }
        

        return Response::json(array(
                    'farmer' => $farmerScope
                ));

    }


    public function synched_up(Request $request){
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            // return $request->farmers;
            $result_array = [];

            if($request->has('farmers') == true){

                foreach ($request->farmers as $key) {
                    // if update date from app > update date in web
                    try {

                        $farmer_old_data = Farmer::where('pcc_system_id', $key['pcc_system_id'])->first();
                        
                        if($farmer_old_data != NULL){
                            $new_program_consent_date_consent_date = null;
                            $new_sms_consent_date_consent_date = null;
                            $old_program_consent_date_consent_date = null;
                            $old_sms_consent_date_consent_date = null;
                            $key['program_consent_date'] = $key['program_consent_date'] ?? null;
                            $key['sms_consent_date'] = $key['sms_consent_date'] ?? null;

                            // Check date for null before Convertion
                            if($key['program_consent_date'] != null){
                                $new_program_consent_date_consent_date = strtotime($key['program_consent_date']);
                                $old_program_consent_date_consent_date = strtotime($farmer_old_data['program_consent_date']);
                            }

                            if($key['sms_consent_date'] != null){
                                $new_sms_consent_date_consent_date = strtotime($key['sms_consent_date']);
                                $old_sms_consent_date_consent_date = strtotime($farmer_old_data['sms_consent_date']);
                            }
                            
                            $check1 = ($new_program_consent_date_consent_date > $old_program_consent_date_consent_date) ? true : false;
                            $check2 = ($new_sms_consent_date_consent_date > $old_sms_consent_date_consent_date) ? true : false;

                            if($farmer_old_data['program_consent_date'] == null){
                                $check1 = true;
                            }

                            if($farmer_old_data['sms_consent_date'] == null){
                                $check2 = true;
                            }

                            Farmer::where("id", $key['id'])
                            ->where("pcc_system_id", $key['pcc_system_id'])
                            ->update(
                                [
                                "pcc_system_id" => $key['pcc_system_id'],
                                "lat" => $key['lat'] ?? null,
                                "longitude" => $key['long'] ?? null,
                                ]
                            );
                                    
                            if($check1 == true){
                                Farmer::where("id", $key['id'])->where('pcc_system_id', $key['pcc_system_id'])->update([
                                    'program_consent_date' => $key['program_consent_date'],
                                    "program_consent" => $key['program_consent'],
                                    "program_consent_method" => "app"
                                ]);
                            }

                            if($check2 == true){
                                Farmer::where("id", $key['id'])->where('pcc_system_id', $key['pcc_system_id'])->update([
                                    'sms_consent_date' => $key['sms_consent_date'],
                                    "sms_consent" => $key['sms_consent'],
                                    "sms_consent_method" => "app"
                                ]);
                            }

                            array_push($result_array, (object)[
                                "pcc_system_id" =>  $key['pcc_system_id'],
                                "status" => "success"
                            ]);
                            
                        }else{
                            array_push($result_array, (object)[
                                "pcc_system_id" =>  $key['pcc_system_id'],
                                "status" => "No Existing Record"
                            ]);
                        }
                        
                    } catch (\Exception $e) {
                        // return  $e->getMessage();
                        array_push($result_array, (object)[
                            "pcc_system_id" =>  $key['pcc_system_id'],
                            "status" => $e->getMessage()
                        ]);
                    }
                    

                }

            }

            DB::commit();
            return response($result_array, 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    }


    public function livestockpregnancy_synchedUp(Request $request){
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            $result_array = [];

            if($request->has('livestocks') == true){

                // loop for obj every livestock id
                foreach ($request->livestocks as $livestock) {

                    foreach ($livestock['pregnancies'] as $pregnancies) {
                        try {
                            Pregnancy::updateOrInsert(
                                [
                                    'external_id' => $pregnancies['external_id'], 'livestock_id' => $livestock['id'],
                                ],
                                [
                                    'start_date' => $pregnancies['start_date'] ?? null,
                                    'end_date' => $pregnancies['end_date'] ?? null,
                                    'description' => $pregnancies['description'] ?? null,
                                    'created_at' => $pregnancies['created_at'] ?? null,
                                    'updated_at' => $pregnancies['updated_at'] ?? null,
                                    'livestock_id' => $pregnancies['livestock_id'] ?? null,
                                    'created_by' => $pregnancies['created_by'] ?? null,
                                    'external_id' => $pregnancies['external_id'] ?? null,
                                ]
                            );
                            // If there is pregnancy record where start date < current date & no end date yet - update livestock column is_pregnant to true
                            $start_date = strtotime($pregnancies['start_date']);
                            $end_date = strtotime($pregnancies['end_date']);
                            $current_date = strtotime(Carbon::now());
    
                            $check = ($start_date < $current_date) ? true : false;
                            if($check == true && $pregnancies['end_date'] == NULL){
                                Livestock::where("id", $livestock['id'])->where('farmer_id', $livestock['farmer_id'])->update(['is_pregnant' => 1]);
                            }else{
                                Livestock::where("id", $livestock['id'])->where('farmer_id', $livestock['farmer_id'])->update(['is_pregnant' => 0]);
                            }
                            
                            $latest_pregnancy = Pregnancy::select('id','external_id','livestock_id')->where('external_id', $pregnancies['external_id'])->where('livestock_id', $livestock['id'])->first();

                            array_push($result_array, (object)[
                                "pregnancy_id" =>  $latest_pregnancy->id,
                                "external_id" =>  $latest_pregnancy->external_id,
                                "livestock_id" =>  $latest_pregnancy->livestock_id,
                                "status" => "success"
                            ]);
                        } catch (\Exception $e) {
                            // Failed
                            // return $e->getMessage();
                            array_push($result_array, (object)[
                                "pregnancy_id" =>  $pregnancies['id'],
                                "external_id" =>  $pregnancies['external_id'],
                                "livestock_id" =>  $livestock['id'],
                                "status" => "failed",
                                "message" => $e->getMessage()
                            ]);
                        }

                    }

                }

            }


            DB::commit();
            return response($result_array, 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }
        ob_end_flush();
    }
    

}
