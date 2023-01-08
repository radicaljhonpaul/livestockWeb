<?php

namespace App\Http\Controllers\Feeds;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

//Models
use App\Models\User;
use App\Models\Category;
use App\Models\UserRoles;

class CategoriesController extends Controller
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

    public function Categories(Request $request)
    {
         //set default name order
         $name_order = 'ASC';

         //override name order if sorted
         if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
             $name_order = request('direction');
         }

        ob_start('ob_gzhandler');
            return Inertia::render('Feeds/CategoriesList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Categories' => Category::orderBy('name', $name_order)->get(),
                'Filters' => request()->all(['field','direction']),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }


    public function CreateCategory(Request $request){


        $validator = Validator::make($request->all(), [

            'name' => ['required', 'max:50'],

        ])->validateWithBag('add');

        Category::create($request->all());
        return redirect()->back();
        
    }



}