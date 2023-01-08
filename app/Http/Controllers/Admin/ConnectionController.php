<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

//Models
use App\Models\User;
use App\Models\UserRoles;
use App\Models\Connection;


class ConnectionController extends Controller
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

    public function Connections(Request $request){

        ob_start('ob_gzhandler');
        return Inertia::render('Admin/ConnectionList', [
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Connections' => Connection::all(),
            'UserRoles' => $this->GetRoles(),
            'Filters' => request()->all(['field','direction']),
        ]);
        ob_end_flush();

    }

    public function CreateConnection(Request $request){

        $validator = Validator::make($request->all(), [

            'name' => ['required', 'max:50', 'unique:connections,name'],
            'description' => ['required', 'max:250'],

        ])->validateWithBag('add');

        DB::beginTransaction();

        Connection::create($request->all());
        
        $connection = Connection::where('name', $request->input('name'))->first();
        $value = $connection->createToken($connection->name)->plainTextToken;
        $connection->value = $value;
        $connection->created_by = $request->user()->id;

        //TODO: add created by based on user
        $connection->save();


        DB::commit();

        return redirect()->back();
        
    }

    // AdminConfig
    public function AdminConfig(Request $request){
    
        ob_start('ob_gzhandler');

        $query = ConfigSetting::query();

        if(request('searchConfig') && (in_array("Admin", $this->GetRoles(), true))){
            $query
            ->where(function ($xx) {
                $xx->where('key','LIKE', '%'.request('searchConfig').'%')
                ->orWhere('value','LIKE', '%'.request('searchConfig').'%')
                ->orWhere('description','LIKE', '%'.request('searchConfig').'%');
            });
        }

        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }
                
        return Inertia::render('Admin/Config', [
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Config' => $query->join('users', 'users.id', '=', 'config_settings.last_update_by')
            ->select(
                'config_settings.*',
                // 'users.first_name as users_first_name',
                // 'users.last_name as users_last_name',
                DB::raw("CONCAT(users.last_name,', ',users.first_name) as last_updated_by"),
            )
            ->orderBy('key', 'asc')
            ->paginate(25)
            ->setPath('')   //for prod ip to url handling
            ->appends($request->query()),
            'UserRoles' => $this->GetRoles(),
            'Filters' => request()->all(['searchConfig','field','direction']),
        ]);
        ob_end_flush();

    }

    public function SaveEditedSpecificConfig(Request $request){
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        try {
            // Update Config
            ConfigSetting::where('id', $request->id)->update(['value' => $request->value, 'description' => $request->description, 'last_update_by' => Auth::id()]);

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        ob_end_flush();
    }
}
