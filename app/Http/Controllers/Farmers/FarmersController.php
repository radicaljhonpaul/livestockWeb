<?php

namespace App\Http\Controllers\Farmers;

use App\Http\Controllers\Controller;
use App\Models\AdminLevelThree;
use App\Models\Farmer;
use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAdminLevelTwo;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

//Export related
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FarmersListExport;
use App\Exports\CarabaoListExport;
use App\Models\Pregnancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FarmersController extends Controller
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

    public function Farmers(Request $request)
    {
        ob_start('ob_gzhandler');
            
            // Get all Areas of Current User
            $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
            // Loop and push to array
            $assignedAreasArr = [];
            $farmersArea = [];
            foreach ($areas as $key) {
                array_push($assignedAreasArr, $key->admin_level_two_id);
            }
            
            $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $assignedAreasArr)->get();
            foreach ($AdminLevelThree as $key) {
                array_push($farmersArea, $key->id);
            }

            $query = Farmer::query();

            if(request('searchFarmers') && (in_array("Admin", $this->GetRoles(), true) || in_array("ReportsUser", $this->GetRoles(), true))){
                
                $query
                ->where(function ($xx) {
                    $xx->where('last_name','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('first_name','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('mobile_number','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('phone_number','LIKE', '%'.request('searchFarmers').'%');
                });

            }else{

                $query
                ->where(function ($xx) {
                    $xx->where('last_name','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('first_name','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('mobile_number','LIKE', '%'.request('searchFarmers').'%')
                    ->orWhere('phone_number','LIKE', '%'.request('searchFarmers').'%');
                });
            }

            if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
                $query->orderBy(request('field'),request('direction'));
            }
            
            if(in_array("Admin", $this->GetRoles(), true) || in_array("ReportsUser", $this->GetRoles(), true)){
                return Inertia::render('Farmers/FarmersList', [
                    'FarmersList' => $query->with(['admin_level_three' => function($level_2){
                        $level_2->with(['adminLevelTwo' => function($level_1){
                            return $level_1->with('adminLevelOne')->get();
                        }])->get();
                    }])
                    // ->whereIn('admin_level_three_id', $farmersArea)
                    ->orderBy('created_at', 'asc')
                    ->paginate()
                    ->setPath('')   //for prod ip to url handling
                    ->appends($request->query()),
                    'UsersDetails' => User::where('id',Auth::id())->get(),
                    'Filters' => request()->all(['searchFarmers','field','direction']),
                    'UserRoles' => $this->GetRoles(),
                ]);

            }else{

                return Inertia::render('Farmers/FarmersList', [
                    'FarmersList' => $query->with(['admin_level_three' => function($level_2){
                        $level_2->with(['adminLevelTwo' => function($level_1){
                            return $level_1->with('adminLevelOne')->get();
                        }])->get();
                    }])
                    ->whereIn('admin_level_three_id', $farmersArea)
                    ->orderBy('created_at', 'asc')
                    ->paginate()
                    ->setPath('')   //for prod ip to url handling
                    ->appends($request->query()),
                    'UsersDetails' => User::where('id',Auth::id())->get(),
                    'Filters' => request()->all(['searchFarmers','field','direction']),
                    'UserRoles' => $this->GetRoles(),
                ]);
            }
            
        ob_end_flush();
    }

    public function SaveEditedFarmerProfile(Request $request)
    {
        ob_start('ob_gzhandler');
            $request->validate([
                'fb_profile' => 'unique:farmers,fb_profile,'.$request->id,
            ]);

            if($request->sms_consent == 1 || $request->sms_consent == '1'){
                Farmer::where('id', $request->id)->update([
                    'sms_consent_date' => $date = Carbon::now(),
                ]);
            }else{
                Farmer::where('id', $request->id)->update([
                    'sms_consent_date' => null,
                ]);
            }

            if($request->program_consent == 1 || $request->program_consent == '1'){
                Farmer::where('id', $request->id)->update([
                    'program_consent_date' => $date = Carbon::now(),
                ]);
            }else{
                Farmer::where('id', $request->id)->update([
                    'program_consent_date' => null,
                ]);
            }

            Farmer::where('id', $request->id)->update([
                'program_consent' => $request->program_consent ?? null,
                'program_consent_method' => 'web',
                'sms_consent' => $request->sms_consent,
                'sms_consent_method' => 'web',
                'mobile_number' => $request->mobile_number ?? '',
                'phone_number' => $request->phone_number ?? '',
                'fb_profile' => $request->fb_profile ?? '',
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back();
        ob_end_flush();
    }
    
    public function SaveCreatedLivestock(Request $request)
    {
        ob_start('ob_gzhandler');
            $request->validate([
                'carabao_code' => 'regex:/^[a-zA-Z0-9\s]+$/|required|min:7|unique:livestocks,carabao_code,'.$request->carabao_code,
                'sex' => 'required',
                'registration_date' => 'required',
                'year_of_birth' => 'nullable|digits:4|integer|min:1900|max:'.(date('Y')+1),
            ]);

            // return dd($request);
            $livestock = new Livestock();
            $livestock->carabao_code = $request->carabao_code;
            $livestock->breed = $request->breed ?? null;
            $livestock->sex = $request->sex;
            $livestock->year_of_birth = $request->year_of_birth ?? null;
            $livestock->registration_date = $request->registration_date;
            $livestock->created_by = Auth::user()->id;
            $livestock->farmer_id = $request->farmer_id;;
            // Default
            $livestock->is_pregnant = 0;
            $livestock->save();

            return redirect()->back();
        ob_end_flush();
    }

    public function DownloadFarmerList(Request $request)
    {
        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $assignedAreasArr = [];
        $farmersArea = [];
        foreach ($areas as $key) {
            array_push($assignedAreasArr, $key->admin_level_two_id);
        }
        
        $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $assignedAreasArr)->get();
        foreach ($AdminLevelThree as $key) {
            array_push($farmersArea, $key->id);
        }

        return Excel::download(new FarmersListExport($farmersArea,$this->GetRoles()), 'Farmer List.csv',
                               \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv',]
        );
    }

    public function SpecificFarmer(Request $request)
    {
        ob_start('ob_gzhandler');
        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $assignedAreasArr = [];
        $farmersArea = [];
        foreach ($areas as $key) {
            array_push($assignedAreasArr, $key->admin_level_two_id);
        }
        
        $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $assignedAreasArr)->get();
        foreach ($AdminLevelThree as $key) {
            array_push($farmersArea, $key->id);
        }

        
        if(in_array("Admin", $this->GetRoles(), true) || in_array("ReportsUser", $this->GetRoles(), true)){
   
            return Inertia::render('Farmers/FarmersProfile', [
                'FarmerDetails' => Farmer::with(['admin_level_three' => function($level_2){
                    $level_2->with(['adminLevelTwo' => function($level_1){
                        return $level_1->with('adminLevelOne')->get();
                    }])->get();
                }])
                ->with(['livestocks.pregnancies' => function($livestock){
                    return $livestock->join('livestocks', 'livestocks.id', '=', 'pregnancies.livestock_id')->select('pregnancies.*', 'livestocks.carabao_code as carabao_code')->latest()->take(5)->get();
                }])
                ->with(['livestocks.visitLogs' => function($livestock){
                    return $livestock->join('livestocks', 'livestocks.id', '=', 'visit_logs.livestock_id')->select('visit_logs.*', 'livestocks.carabao_code as carabao_code', 'livestocks.id as livestock_id')->latest()->take(5)->get();
                }])
                ->where('id', $request->farmer_id)
                ->get(),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchLivestock','field','direction']),
                "PrevUrl" => $request->prev_url,
                'UserRoles' => $this->GetRoles(),
            ]);

        }else{
            
            return Inertia::render('Farmers/FarmersProfile', [
                'FarmerDetails' => Farmer::with(['admin_level_three' => function($level_2){
                    $level_2->with(['adminLevelTwo' => function($level_1){
                        return $level_1->with('adminLevelOne')->get();
                    }])->get();
                }])
                ->with(['livestocks.pregnancies' => function($livestock){
                    return $livestock->join('livestocks', 'livestocks.id', '=', 'pregnancies.livestock_id')->select('pregnancies.*', 'livestocks.carabao_code as carabao_code')->latest()->take(5)->get();
                }])
                ->with(['livestocks.visitLogs' => function($livestock){
                    return $livestock->join('livestocks', 'livestocks.id', '=', 'visit_logs.livestock_id')->select('visit_logs.*', 'livestocks.carabao_code as carabao_code', 'livestocks.id as livestock_id')->latest()->take(5)->get();
                }])
                ->whereIn('admin_level_three_id', $farmersArea)
                ->where('id', $request->farmer_id)
                ->get(),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchLivestock','field','direction']),
                "PrevUrl" => $request->prev_url,
                'UserRoles' => $this->GetRoles(),
            ]);
        }
        
        ob_end_flush();
    }

    public function DownloadLivestockList(Request $request)
    {
        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $assignedAreasArr = [];
        $farmersArea = [];
        $farmersIDs = [];

        foreach ($areas as $key) {
            array_push($assignedAreasArr, $key->admin_level_two_id);
        }
        
        $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $assignedAreasArr)->get();
        foreach ($AdminLevelThree as $key) {
            array_push($farmersArea, $key->id);
        }

        $Farmers = Farmer::whereIn('admin_level_three_id', $farmersArea)->get();
        foreach ($Farmers as $key) {
            array_push($farmersIDs, $key->id);
        }

        return Excel::download(new CarabaoListExport($farmersIDs,$this->GetRoles()), 'Carabao List.csv',
            \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv',]
        );

    }
    

    public function SpecificLivestock(Request $request)
    {
        ob_start('ob_gzhandler');
        // return "request";
        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $assignedAreasArr = [];
        $farmersArea = [];
        $farmersIDs = [];
        foreach ($areas as $key) {
            array_push($assignedAreasArr, $key->admin_level_two_id);
        }

        $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $assignedAreasArr)->get();
        foreach ($AdminLevelThree as $key) {
            array_push($farmersArea, $key->id);
        }

        $Farmers = Farmer::whereIn('admin_level_three_id', $farmersArea)->get();
        foreach ($Farmers as $key) {
            array_push($farmersIDs, $key->id);
        }

        if(in_array("Admin", $this->GetRoles(), true) || in_array("ReportsUser", $this->GetRoles(), true)){
            return Inertia::render('Farmers/LivestockProfile', [
                'LivestockDetails' => Livestock::with(['farmer' => function($farmer){
                    //added this section for cleaner & reusable pulling of farmer & location info directly from farmer level w/o manual joins
                    return $farmer->select('id','first_name', 'last_name', 'admin_level_three_id', 'mobile_number')
                           ->with(['admin_level_three' => function($level_2){
                                $level_2->with(['adminLevelTwo' => function($level_1){
                                    return $level_1->with('adminLevelOne')->get();
                                }])
                        ->get();
                    }])
                    ->get();
                }])
                ->with(['visitLogs' => function($visitlogs){
                    return $visitlogs
                    ->with(['diagnosisLog' => function($diagnosisLog){
                        return $diagnosisLog->with(['livestock' => function($livestock){
                            
                            //note: this will not be easily changed as the diagnosis log modal is used both in top health & carbao visit log info
                            return $livestock->with(['farmer' => function($livestock){
                                return $livestock->select('id','admin_level_three_id')->with(['admin_level_three' => function($level_2){
                                    $level_2->with(['adminLevelTwo' => function($level_1){
                                        return $level_1->with('adminLevelOne')->get();
                                    }])->get();
                                }])->get();
                            }])
                            ->with('pregnancies')
                            ->get();
                        }])
                        ->with('authorizedBy')
                        ->with('mediaDiagnosisLogs')
                        ->with('interventions')
                        ->with('symptoms')
                        ->with('healthConditions')
                        ->join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
                        ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
                        ->join('users', 'diagnosis_logs.assigned_to', '=', 'users.id')
                        ->select('users.id as assigned_to_id', 'users.first_name as assigned_to_first_name', 'users.last_name as assigned_to_last_name', 'diagnosis_logs.*', 'farmers.id as joined_farmers_id', 'farmers.last_name as joined_farmers_last_name', 'farmers.first_name as joined_farmers_first_name', 'farmers.mobile_number as joined_farmers_mobile_number', 'joined_livestocks.farmer_id', 'diagnosis_logs.assigned_to', 'joined_livestocks.id as joined_livestock_id', 'joined_livestocks.farmer_id', 'joined_livestocks.carabao_code')
                        ->get();
                    }])
                    
                    ->with('assignedTo')
                    ->with(['feedingLog' => function($feedingLog){
                        return $feedingLog->with(['ingredients' => function($query){
                            return $query
                                    ->join('categories', 'ingredients.category_id', '=', 'categories.id')
                                    ->select('ingredients.id as ingredient_id', 'ingredients.name as ingredient_name', 'categories.name as category_name')
                                    ->get();
                        }])
                        ->with('createdBy:id,first_name,last_name')
                        ->with('nutrientDetails')
                        ->get();
                    }])
    
                    ->orderBy('visit_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
                }])
                ->where('id', $request->livestock_id)
                ->paginate()
                // ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchLivestock','field','direction']),
                'UserRoles' => $this->GetRoles(),
                'Prev_Url' => $request->prev_url,
            ]);
        }else{
            return Inertia::render('Farmers/LivestockProfile', [
                'LivestockDetails' => Livestock::with(['farmer' => function($farmer){
                    //added this section for cleaner & reusable pulling of farmer & location info directly from farmer level w/o manual joins
                    return $farmer->select('id','first_name', 'last_name', 'admin_level_three_id', 'mobile_number')
                           ->with(['admin_level_three' => function($level_2){
                                $level_2->with(['adminLevelTwo' => function($level_1){
                                    return $level_1->with('adminLevelOne')->get();
                                }])
                        ->get();
                    }])
                    ->get();
                }])
                ->with(['visitLogs' => function($visitlogs){
                    return $visitlogs
                    ->with(['diagnosisLog' => function($diagnosisLog){
                        return $diagnosisLog->with(['livestock' => function($livestock){
                            
                            //note: this will not be easily changed as the diagnosis log modal is used both in top health & carbao visit log info
                            return $livestock->with(['farmer' => function($livestock){
                                return $livestock->select('id','admin_level_three_id')->with(['admin_level_three' => function($level_2){
                                    $level_2->with(['adminLevelTwo' => function($level_1){
                                        return $level_1->with('adminLevelOne')->get();
                                    }])->get();
                                }])->get();
                            }])
                            ->with('pregnancies')
                            ->get();
                        }])
                        ->with('authorizedBy')
                        ->with('mediaDiagnosisLogs')
                        ->with('interventions')
                        ->with('symptoms')
                        ->with('healthConditions')
                        ->join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
                        ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
                        ->join('users', 'diagnosis_logs.assigned_to', '=', 'users.id')
                        ->select('users.id as assigned_to_id', 'users.first_name as assigned_to_first_name', 'users.last_name as assigned_to_last_name', 'diagnosis_logs.*', 'farmers.id as joined_farmers_id', 'farmers.last_name as joined_farmers_last_name', 'farmers.first_name as joined_farmers_first_name', 'farmers.mobile_number as joined_farmers_mobile_number', 'joined_livestocks.farmer_id', 'diagnosis_logs.assigned_to', 'joined_livestocks.id as joined_livestock_id', 'joined_livestocks.farmer_id', 'joined_livestocks.carabao_code')
                        ->get();
                    }])
                    
                    ->with('assignedTo')
                    ->with(['feedingLog' => function($feedingLog){
                        return $feedingLog->with(['ingredients' => function($query){
                            return $query
                                    ->join('categories', 'ingredients.category_id', '=', 'categories.id')
                                    ->select('ingredients.id as ingredient_id', 'ingredients.name as ingredient_name', 'categories.name as category_name')
                                    ->get();
                        }])
                        ->with('createdBy:id,first_name,last_name')
                        ->with('nutrientDetails')
                        ->get();
                    }])
    
                    ->orderBy('visit_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
                }])
                ->where('id', $request->livestock_id)
                ->whereIn('farmer_id', $farmersIDs)
                ->paginate()
                // ->setPath('')   //for prod ip to url handling
                ->appends($request->query()),
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'Filters' => request()->all(['searchLivestock','field','direction']),
                'UserRoles' => $this->GetRoles(),
                'Prev_Url' => $request->prev_url,
            ]);
        }
        
        ob_end_flush();
    }
    

    public function PregnancyInfo(Request $request)
    {
        ob_start('ob_gzhandler');
        // return $request;
        
        $Livestock = Livestock::where('carabao_code', $request->carabao_code)->first();
        $Pregnancies = Pregnancy::where('livestock_id', $Livestock->id)->get();
        // return $Pregnancies;

        return Inertia::render('Farmers/PregnancyList', [
            'PregnancyDetails' => Pregnancy::with('livestock.farmer')->where('livestock_id', $Livestock->id)
            ->paginate()
            ->setPath('')   //for prod ip to url handling
            ->appends($request->query()),
            'UsersDetails' => User::where('id',Auth::id())->get(),
            'Filters' => request()->all(['searchLivestock','field','direction']),
            'UserRoles' => $this->GetRoles(),
            'Prev_Url' => $request->prev_url,
            'Livestock_ID' => $Livestock->id,
        ]);
        ob_end_flush();
    }
    
}
