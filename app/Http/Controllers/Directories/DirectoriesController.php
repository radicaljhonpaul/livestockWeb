<?php

namespace App\Http\Controllers\Directories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use App\Models\UserRoles;
use App\Models\User;


class DirectoriesController extends Controller
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

    public function Directories(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Directories/DirectoryList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }
}
