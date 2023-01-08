<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    
    public function Login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();   
        // return Hash::check($request->password, $user->password);

        if ($user == null || !$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid Credentials'], 401);
            // throw ValidationException::withMessages([
            //     'email' => ['The provided credentials are incorrect.'],
            // ]);
        }else{

            $roles = UserRoles::where('user_id', $user->id)->get();
            // Store Roles in an Array
            $rolesArray = [];
            foreach ($roles as $key) {
                # code...
                array_push($rolesArray, $key->role);
            }

            // Check if rolesArray has Vet or VetAide
            if(in_array("Vet", $rolesArray) || in_array("VetAide", $rolesArray)){
                return response([
                    'user' => $user,
                    'role' => $rolesArray,
                    'token' => $user->createToken($request->email)->plainTextToken,
                ]);
            }else{
                return response(['message' => 'Unable to login, assigned roles should either Vet or Vet Aide. Please contact your Administrator regarding this error.'], 401);
            }
        }
        
        // return response([
        //     'user' => $user,
        //     'token' => $user->createToken($request->email)->plainTextToken,
        // ]);
    }

    public function User(Request $request) {
        return $request->user();
    }
}
