<?php

namespace App\Http\Controllers\Feeds;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//Models
use App\Models\User;
use App\Models\SrpYear;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientSrpYear;

//Export related
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngredientSrpYearExport;
use App\Imports\PriceListImport;
use App\Models\UserRoles;


class SrpYearController extends Controller
{
    public function GetRoles()
    {
        $data = UserRoles::where('user_id',auth()->user()->id)->get();
        $role_arr = [];
        foreach ($data as $key) {
            array_push($role_arr, $key->role);
        }

        return $role_arr;
    }

    //Gets List of SRP Years
    public function SrpYears(Request $request)
    {
        
        //set default name order
        $year_order = 'ASC';

        //override name order if sorted
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $year_order = request('direction');
        }

        ob_start('ob_gzhandler');
            return Inertia::render('Pricing/SrpYearList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'SrpYears' => SrpYear::orderBy('year', $year_order)->get(),
                'Filters' => request()->all(['field','direction']),
                'UserRoles' => $this->GetRoles(),

            ]);
        ob_end_flush();
    }


    
    //Gets List of Ingredients and Price by SrpYearId
    public function PriceByYear(Request $request)
    {

        ob_start('ob_gzhandler');
            return Inertia::render('Pricing/IngredientPriceList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'IngredientSrpYears' => Ingredient::join('ingredient_srp_year', 'ingredient_srp_year.ingredient_id', '=', 'ingredients.id')
                                        ->where('ingredient_srp_year.srp_year_id', $request->srpyear_id)
                                        ->orderBy('ingredients.category_id','ASC')
                                        ->join('categories', 'categories.id', '=', 'ingredients.category_id')
                                        ->select('categories.name AS category_name','ingredients.*', 'ingredient_srp_year.price', 'ingredient_srp_year.srp_year_id', 'ingredient_srp_year.id')
                                        ->orderBy('ingredients.name','ASC')
                                        ->paginate(20)
                                        ->setPath('')   //for prod ip to url handling
                                        ->appends($request->query()),
                'SrpYear' => SrpYear::where('id', $request->srpyear_id)->get()->first(),
                'UserRoles' => $this->GetRoles(),

            ]);
        ob_end_flush();
    }
    

    public function DownloadPriceBySrp(Request $request){
        return Excel::download(new IngredientSrpYearExport($request->srpyear_id), '['.$request->srp_name.'] Price List.csv',
                               \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv',]
        );

    }

    public function UploadPriceBySrp(Request $request){

        // return $request->srpyear_id;
        $path = $request->csv_file[0]->getRealPath();

        // Excel::import(new PriceListImport([$request->srpyear_id]), $path);
        $import = new PriceListImport([$request->srpyear_id]);
        $import->import($path);

        // return dd($import->failures());
        if($import->failures()->isNotEmpty()){
            return redirect()->back()->withErrors($import->failures()->toArray());
        }else{
            return redirect()->back();
        }

    }
    
    public function CreateSrpYear(Request $request){

        $validator = Validator::make($request->all(), [
            'year' => ['required', 'max:50', 'regex:/(^(\d{4})([a-zA-z\s]+)?$)/u'],
        ])->validateWithBag('add');
       
        DB::beginTransaction();
            // Create SRPYear
        try {

            $srp = new SrpYear;
            $srp->year = $request->year;
            $srp->is_active = 0;
            $srp->save();
            
            $ingredientList = Ingredient::all()->pluck('id');      
            
            //$ingArr = [];
            
            foreach($ingredientList as $ingredient){
               
                IngredientSrpYear::create([

                    'ingredient_id' => $ingredient,
                    'srp_year_id' => $srp->id,
                    'price' => 1.0,

                ]);
               
            }
            
            DB::commit();

        }catch (\Exception $e) {
            DB::rollback();
            return response(['message' => $e->getMessage()], 401);
        }


        return redirect()->back();

    }

    public function ActivateSrpYear(Request $request){

        ob_start('ob_gzhandler');
        DB::beginTransaction();
      
        try {

            SrpYear::updateOrInsert(
                ['id' => $request->srp_year_id],
                ['is_active' => $request->is_active]
            );

            DB::commit();
            return Redirect::back()->with('message','Srp Year Activation Status Updated Successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        ob_end_flush();


    }


}