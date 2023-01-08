<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;


use App\Models\UserAdminLevelTwo;
use App\Models\AdminLevelTwo;
use App\Models\User;
use App\Models\UserRoles;

use Illuminate\Http\Request;
use Response;

class UserDirectoryController extends Controller
{
    
    /**
     * Get Vet and VetAide Users in the same area as assigned user
     * PCCP2-457
     */
    public function getAreaUsers(Request $request){


        $areaUserIds = UserAdminLevelTwo::whereIn('admin_level_two_id', $request->user()->adminLevelTwos->pluck('id'))
                       ->select('user_id') 
                       ->get()->pluck('user_id');

    
        $users = User::whereHas('userRoles', $roleFilter = function($query){
                        $query
                            ->where('role', 'Vet')
                            ->orWhere('role', 'VetAide');
                })
                ->with('userRoles:user_id,role')
                ->whereIn('id', $areaUserIds)
                ->select('id', 'last_name', 'first_name', 'mobile_number')
                ->get();

        
        return Response::json(array(

                    'users' => $users,
                    
                    ));         

    }


}
