<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

//Models
use App\Models\User;
use App\Models\UserRoles;
use App\Models\VisitRating;

class VisitRatingController extends Controller
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


    public function VisitRatings(Request $request){

        
          //set default name order
          $date_order = 'DESC';

          //override name order if sorted
          if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
              $date_order = request('direction');
          }
 
         ob_start('ob_gzhandler');
             return Inertia::render('Reports/VisitRatingList', [
                 'UsersDetails' => User::where('id',Auth::id())->get(),
                 'VisitRatings' => VisitRating::orderBy('visit_date', $date_order)->with('assignedTo', 'createdBy')->get(),
                 'Filters' => request()->all(['field','direction']),
                 'UserRoles' => $this->GetRoles(),
             ]);
         ob_end_flush();
         

       //  return "hello visit ratings";


    }



}
