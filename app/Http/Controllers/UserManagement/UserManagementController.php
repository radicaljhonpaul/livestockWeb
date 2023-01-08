<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\AdminLevelOne;
use App\Models\AdminLevelThree;
use App\Models\AdminLevelTwo;

use App\Models\UserAdminLevelTwo;
use App\Models\UserRoles;

class UserManagementController extends Controller
{

    public $checkedStaffs = [];

    public function GetRoles()
    {
        $data = UserRoles::where('user_id',auth()->user()->id)->get();
        $role_arr = [];
        foreach ($data as $key) {
            array_push($role_arr, $key->role);
        }

        return $role_arr;
    }

    public function Users(Request $request)
    {
        $request->session()->forget('checkedStaffs');

        ob_start('ob_gzhandler');
        // return User::get();
        $query = User::query();
        if(request('searchUsers')){
            $query
            ->where('last_name','LIKE', '%'.request('searchUsers').'%')
            ->orWhere('first_name','LIKE', '%'.request('searchUsers').'%')
            ->orWhere('email','LIKE', '%'.request('searchUsers').'%')
            ->get();
        }
    
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }

        $UserAdminLevelTwo = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        $UserAdminLevelTwoArray = [];
        foreach ($UserAdminLevelTwo as $key) {
            array_push($UserAdminLevelTwoArray, $key->admin_level_two_id);
        }

        $UserAdminLevelTwoUsers = UserAdminLevelTwo::whereIn('admin_level_two_id', $UserAdminLevelTwoArray)->get();

        $UsersArrayWithSameArea = [];
        foreach ($UserAdminLevelTwoUsers as $key) {
            array_push($UsersArrayWithSameArea, $key->user_id);
        }

        if(in_array("Admin", $this->GetRoles(), true)){
            return Inertia::render('Staffings/StaffsList', [
                'StaffList' => $query
                    ->with('userRoles')
                    ->with('adminLevelTwos')
                    ->paginate()
                    ->setPath('')   //for prod ip to url handling
                    ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchUsers','field','direction']),
                'UserRoles' => $this->GetRoles(),
    
            ]);
        }else{
            return Inertia::render('Staffings/StaffsList', [
                'StaffList' => $query
                ->with('userRoles')
                ->with('adminLevelTwos')
                ->whereIn('id', $UsersArrayWithSameArea)
                ->paginate()
                ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchUsers','field','direction']),
                'UserRoles' => $this->GetRoles(),
    
            ]);
        }

        ob_end_flush();
    }

    public function SpecificStaff(Request $request)
    {
        ob_start('ob_gzhandler');
        
        // return $request;
        return Inertia::render('Staffings/StaffsProfile', [
            'StaffsDetails' => User::where('id', $request->staff_id)
            ->get(),
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Filters' => request()->all(['searchUsers','field','direction']),
            "PrevUrl" => $request->prev_url,
            'UserRoles' => $this->GetRoles(),
        ]);
        ob_end_flush();
    }

    public function ChangePasswordPage(Request $request)
    {

        ob_start('ob_gzhandler');
        return Inertia::render('Profile/ChangePassword', [
            'UsersDetails' => User::where('id', Auth::id())
            ->get(),
            'UserRoles' => $this->GetRoles(),
        ]);
        ob_end_flush();

        // ob_start('ob_gzhandler');
        // DB::beginTransaction();

        // try {

        //     DB::commit();
        //     return redirect()->back();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $e->getMessage();
        // }
        
        // ob_end_flush();
    }

    public function ChangePassword(Request $request)
    {

        if(strcmp($request->old_password, $request->password) == 0){
            // Current password and new password same
            return redirect()->back()->withErrors("New Password cannot be same as your current password.");
        }
        // return $request;

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|max:255|min:8',
            'old_password' => ['required', function ($attribute, $value, $fail) use ($request) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ]);
        // return Hash::make($request->old_password);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }else{
            // update to pass
            User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back();
        }
        ob_end_flush();
    }

    public function CreateStaff(Request $request)
    {
        ob_start('ob_gzhandler');
        DB::beginTransaction();
        // Update HealthCondition
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'mobile_number' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'user_role' => ['required', 'array', 'max:255'],
                'password' => ['required', 'string', 'confirmed'],
            ]);
                
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }else{
                $new_staff = new User();
                $new_staff->first_name = $request->first_name;
                $new_staff->last_name = $request->last_name;
                $new_staff->mobile_number = $request->mobile_number;
                $new_staff->username = $request->username;
                $new_staff->email = $request->email;
                $new_staff->password = Hash::make($request->password);
                $new_staff->save();
    
                // Save to user roles
                foreach ($request->user_role as $key => $value) {
                    $role = new UserRoles();
                    $role->user_id = $new_staff->id;
                    $role->role = $value;
                    $role->save();
                }
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        
        ob_end_flush();
    }
    
    // Get all Provinces
    public function ViewProvinces(Request $request)
    {
        ob_start('ob_gzhandler');

        $query = AdminLevelOne::query();
        if(request('searchProvinces')){
            $query
            ->where('name','LIKE', '%'.request('searchProvinces').'%')
            ->get();
        }
    
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }
        // return $request;
        return Inertia::render('Staffings/Location/Province', [
            'ProvinceList' => $query->orderBy('name', 'asc')
                                ->paginate()
                                ->setPath('')   //for prod ip to url handling
                                ->appends($request->query()),
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Filters' => request()->all(['searchProvinces','field','direction']),
            'UserRoles' => $this->GetRoles(),
        ]);
        ob_end_flush();
    }

    public function ViewCities(Request $request)
    {
        ob_start('ob_gzhandler');
        $query = AdminLevelTwo::query();
        if(request('searchCity')){
            $query
            ->where('name','LIKE', '%'.request('searchCity').'%')
            ->get();
        }

        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }

        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $assignedAreasArr = [];
        foreach ($areas as $key) {
            array_push($assignedAreasArr, $key->admin_level_two_id);
        }

        if(in_array("Admin", $this->GetRoles(), true)){

            return Inertia::render('Staffings/Location/City', [
                'CityList' => $query
                ->orderBy('name', 'asc')
                ->with('adminLevelOne')
                ->withCount('users')
                ->where('admin_level_one_id', $request->prov_id)
                ->paginate()
                ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchCity','field','direction']),
                'UserRoles' => $this->GetRoles(),
            ]);

        }else{
            return Inertia::render('Staffings/Location/City', [
                'CityList' => $query
                ->orderBy('name', 'asc')
                ->with('adminLevelOne')
                ->withCount('users')
                ->where('admin_level_one_id', $request->prov_id)
                ->whereIn('id', $assignedAreasArr)
                ->paginate()
                ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchCity','field','direction']),
                'UserRoles' => $this->GetRoles(),
            ]);
        }
        ob_end_flush();
    }

    
    public function ViewBrgy(Request $request)
    {
        ob_start('ob_gzhandler');

        $query = AdminLevelThree::query();
        if(request('searchBrgy')){
            $query
            ->where('name','LIKE', '%'.request('searchBrgy').'%')
            ->get();
        }
    
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }

        // return $request;
        return Inertia::render('Staffings/Location/Brgy', [
            'BrgyList' => $query->orderBy('name', 'asc')->withCount('farmers')->with('adminLevelTwo.adminLevelOne')->where('admin_level_two_id', $request->city_id)
                                                        ->paginate()->setPath('')->appends($request->query()),
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Filters' => request()->all(['searchBrgy','field','direction']),
            'UserRoles' => $this->GetRoles(),
        ]);
        ob_end_flush();
    }

    public function AssignUsers(Request $request)
    {
        ob_start('ob_gzhandler');
        $query = User::query();
        if(request('searchStaff')){

            $query
            ->where('last_name','LIKE', '%'.request('searchStaff').'%')
            ->orWhere('first_name','LIKE', '%'.request('searchStaff').'%')
            ->orWhere('email','LIKE', '%'.request('searchStaff').'%')
            ->get();
        }
    
        if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
            $query->orderBy(request('field'),request('direction'));
        }

        $array_id_of_staff_with_already_assigned = [];
        if(request('city_id')){
            $staffs = $query->with(['adminLevelTwos' => function($adminLevelTwos){
                return $adminLevelTwos->get();
            }])->get();
    
            foreach ($staffs as $key_level1) {
                # code...
                if(sizeof($key_level1->adminLevelTwos) > 0){
                    foreach ($key_level1->adminLevelTwos as $key_level2) {
                        # code...
                        if($key_level2->id == $request->city_id){
                            array_push($array_id_of_staff_with_already_assigned, $key_level2->pivot->user_id);
                        }
                    }
                }
            }
        }
        
        return Inertia::render('Staffings/Location/AssignUsers', [
            // Check for adminlevetwo's == $request->city_id
            'CityAndUsersList' => $query
            ->with('userRoles')
            ->with(['adminLevelTwos' => function($adminLevelTwos){
                    return $adminLevelTwos->get();
                }])
            // ->with('adminLevelTwos')
            ->paginate()
            ->setPath('')   //for prod ip to url handling
            ->appends($request->query()),
            'City' => AdminLevelTwo::with('adminLevelOne')->where('id',$request->city_id)->get(),
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Filters' => request()->all(['city_id','searchStaff','field','direction']),
            'UserRoles' => $this->GetRoles(),
            'AssignedDefault' => $array_id_of_staff_with_already_assigned,
        ]);
        ob_end_flush();
    }

    public function SaveAssignedUsers(Request $request)
    {
        ob_start('ob_gzhandler');
            DB::beginTransaction();
            // SaveAssignedUsers
            try {

                // return $request;
                // get all UserAdminLevelTwo where equal to city id
                $UserAdminLevelTwoArr = [];
                $UserAdminLevelTwoData = UserAdminLevelTwo::where('admin_level_two_id',$request[1])->get(); 
                foreach($UserAdminLevelTwoData as $key){
                    array_push($UserAdminLevelTwoArr, $key->user_id);
                }
                
                // return [$UserAdminLevelTwoArr, $request[0], array_values(array_diff($UserAdminLevelTwoArr, $request[0]))];
                // return array_values(array_diff($UserAdminLevelTwoArr, $request[0]));

                for ($i=0; $i < sizeof($request[0]) ; $i++) { 
                    # code...
                    UserAdminLevelTwo::updateOrInsert(
                        ['user_id' => $request[0][$i], 'admin_level_two_id' => $request[1]],
                        ['user_id' => $request[0][$i], 'admin_level_two_id' => $request[1]]
                    );
                }

                UserAdminLevelTwo::whereIn('user_id', array_values(array_diff($UserAdminLevelTwoArr,$request[0])))->where('admin_level_two_id', $request[1])->delete();

                DB::commit();
                return Redirect::back()->with('message','Staff Location Assignment Updated Successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
        ob_end_flush();
    }


    public function DirectSaveAssignedUsers(Request $request)
    {
        ob_start('ob_gzhandler');
            DB::beginTransaction();
            // SaveAssignedUsers
            try {

                if($request->type == 1){
                    UserAdminLevelTwo::updateOrInsert(
                        ['user_id' => $request->user_id, 'admin_level_two_id' => $request->city_id],
                        ['user_id' => $request->user_id, 'admin_level_two_id' => $request->city_id]
                    );
                }else if($request->type == 2){
                    UserAdminLevelTwo::where('user_id', $request->user_id)->where('admin_level_two_id', $request->city_id)->delete();
                }else{

                }

                DB::commit();
                return Redirect::back()->with('message','Staff Location Assignment Updated Successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
            ob_end_flush();
    }
}
