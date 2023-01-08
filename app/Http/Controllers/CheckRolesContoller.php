<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CheckRolesContoller extends Controller
{
    //
    public function index(Request $request)
    {
        return Inertia::render('CheckRole', [
            'UserRoles' => UserRoles::where('user_id',Auth::id())->get(),
        ]);
    }
}
