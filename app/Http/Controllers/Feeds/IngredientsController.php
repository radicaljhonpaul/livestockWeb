<?php

namespace App\Http\Controllers\Feeds;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//Models
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\UserRoles;
use App\Models\SrpYear;
use App\Models\IngredientSrpYear;

class IngredientsController extends Controller
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

    //Gets List of Ingredients by CategoryId
    public function IngredientsByCategory(Request $request)
    {
        //set default name order
        $name_order = 'ASC';

        //override name order if sorted
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $name_order = request('direction');
        }


        ob_start('ob_gzhandler');
            return Inertia::render('Feeds/IngredientsList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Ingredients' => Ingredient::with('category:id,name')->where('category_id', $request->category_id)
                                ->orderBy('name', $name_order)
                                ->paginate()
                                ->setPath('')   //for prod ip to url handling
                                ->appends($request->query()),
                'Category' => Category::where('id', $request->category_id)->get()->first(),
                'Filters' => request()->all(['field','direction']),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();



    }

    
    public function CreateIngredient(Request $request){


        $validator = Validator::make($request->all(), [

            'name' => ['required', 'max:50'],
            'dm' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'me' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'cp' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'ndf' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'tdn' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'ca' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'p' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'min' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'max' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],

        ])->validateWithBag('add');

        DB::beginTransaction();
        
        //Create default price of 1 for each srp

        try {

            $ingredient = Ingredient::create($request->all());

            $srpYearList = SrpYear::all()->pluck('id');                      
                foreach($srpYearList as $srpYear){
            
                    IngredientSrpYear::create([
                        'ingredient_id' => $ingredient->id,
                        'srp_year_id' => $srpYear,
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

    public function SaveEditedIngredient(Request $request){

        $validator = Validator::make($request->all(), [

            'name' => ['required', 'max:50'],
            'dm' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'me' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'cp' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'ndf' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'tdn' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'ca' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'p' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'min' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'max' => ['nullable', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],

        ])->validateWithBag('edit');

        $ingredient = Ingredient::find($request->id);
        $ingredient->name = $request->name;
        $ingredient->dm = $request->dm;
        $ingredient->me = $request->me;
        $ingredient->cp = $request->cp;
        $ingredient->ndf = $request->ndf;
        $ingredient->tdn = $request->tdn;
        $ingredient->ca = $request->ca;
        $ingredient->p = $request->p;
        $ingredient->min = $request->min;
        $ingredient->max = $request->max;
        $ingredient->save();

        return redirect()->back();

    }


   
}
