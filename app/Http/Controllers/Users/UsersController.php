<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\HcSymptom;
use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaHealthCondition;
use App\Models\MediaSymptom;
use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;
use App\Models\User;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        // return $request;
        $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
            //  $token = $user->createToken('my-app-token')->plainTextToken;
             $token = $user->createToken('pcc-qrt-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
    }

    function sample(Request $request)
    {   
        $response = [
            'user' => "ddsds",
            'token' => "token"
        ];    

        return $response;
             return response($response, 201);
    }
}
