<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request){

        // Delete access token.
        $res = request()->user()->currentAccessToken()->delete();
        if($res == 1){
            return response(['message' => 'Logged Out Successfully']);
        }
    }
}
