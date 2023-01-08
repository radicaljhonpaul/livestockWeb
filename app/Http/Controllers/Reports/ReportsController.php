<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminLevelOne;
use App\Models\AdminLevelThree;
use App\Models\AdminLevelTwo;
use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogHealthCondition;
use App\Models\DiagnosisLogIntervention;
use App\Models\DiagnosisLogSymptom;
use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\MediaDiagnosisLog;
use App\Models\UserAdminLevelTwo;
use App\Models\UserRoles;
use App\Models\VisitLog;
use Database\Factories\DiagnosisLogFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

//Export related
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TopHealthIssuesExport;
use App\Exports\TopHealthIssuesHealthConditionExport;
use App\Exports\TopHealthIssuesInterventionsByVetExport;
use App\Exports\TopHealthIssuesInterventionsExport;
use App\Exports\TopHealthIssuesMultiSheetExport;
use App\Exports\TopHealthIssuesSymptomsExport;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
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

    public function Reports(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Reports/ReportsList', [
                'UsersDetails' => User::where('id',Auth::id())->get(),
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    public function ReportsHealthVisit(Request $request)
    {
        ob_start('ob_gzhandler');

            // Get areas
            // Get all Areas of Current User
            $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
            // Loop and push to array
            $UserAdminLevelTwo = [];
            $LevelThree = [];
            foreach ($areas as $key) {
                array_push($UserAdminLevelTwo, $key->admin_level_two_id);
            }

            $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $UserAdminLevelTwo)->get();
            foreach ($AdminLevelThree as $key) {
                array_push($LevelThree, $key->id);
            }

            // Get all farmer in LevelThree
            $FarmersArray = [];
            $Farmers = Farmer::whereIn('admin_level_three_id', $LevelThree)->get();
            foreach ($Farmers as $key) {
                array_push($FarmersArray, $key->id);
            }

            // Get all livestock in area
            $LivestockArray = [];
            $Livestock = Livestock::whereIn('farmer_id', $FarmersArray)->get();
            foreach ($Livestock as $key) {
                array_push($LivestockArray, $key->id);
            }

            $query = DiagnosisLog::query()->with(['livestock' => function($livestock){
                return $livestock
                ->with(['farmer' => function($livestock){
                    return $livestock->select('id','admin_level_three_id')->with(['admin_level_three' => function($level_2){
                        $level_2->with(['adminLevelTwo' => function($level_1){
                            return $level_1->with('adminLevelOne')->get();
                        }])->get();
                    }])->get();
                }])
                ->with('pregnancies')
                ->get();
            }])
            ->with('assignedTo')
            ->with('authorizedBy')
            ->with('visitLog')
            ->with('mediaDiagnosisLogs')
            ->with('interventions')
            ->with('symptoms')
            ->with('healthConditions')
            ->join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
            ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
            ->join('users', 'diagnosis_logs.assigned_to', '=', 'users.id')
            ->join('admin_level_twos as district', 'farmers.admin_level_three_id', '=', 'district.id')
            ->select(
                'users.id as assigned_to_id',
                'users.first_name as assigned_to_first_name',
                'users.last_name as assigned_to_last_name',
                'diagnosis_logs.*',
                'farmers.id as joined_farmers_id',
                'farmers.last_name as joined_farmers_last_name',
                'farmers.first_name as joined_farmers_first_name',
                'farmers.mobile_number as joined_farmers_mobile_number',
                'farmers.admin_level_three_id as joined_farmers_admin_level_three_id',
                'joined_livestocks.farmer_id',
                'diagnosis_logs.assigned_to',
                'joined_livestocks.id as joined_livestock_id',
                'joined_livestocks.farmer_id',
                'joined_livestocks.carabao_code',
                'district.id as joined_admin_level_twos_id',
                'district.name as joined_admin_level_twos_name'
            );

            if(request('searchTopHealthIssues') && in_array("Admin", $this->GetRoles(), true)){
                $query
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();

            }else if(request('searchTopHealthIssues') && in_array("ReportsUser", $this->GetRoles(), true)){
                $query
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();
                
            }else if(request('searchTopHealthIssues') && in_array("Vet", $this->GetRoles(), true)){
                $query
                ->whereIn('livestock_id', $LivestockArray)
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();
            
            }else if(request('searchTopHealthIssues') && in_array("VetAide", $this->GetRoles(), true)){
                $query
                ->whereIn('livestock_id', $LivestockArray)
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();
            }else if(request('searchTopHealthIssues') && in_array("IHealthContentManager", $this->GetRoles(), true)){
                $query
                ->whereIn('livestock_id', $LivestockArray)
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();
            }else if(request('searchTopHealthIssues') && in_array("IFodderContentManager", $this->GetRoles(), true)){
                $query
                ->whereIn('livestock_id', $LivestockArray)
                ->where(function ($query) {
                    $query->orWhere('notes','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('assessment','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('activity_type','LIKE', '%'.request('searchTopHealthIssues').'%')
                    // ->orWhere('status','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('district.name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.last_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('farmers.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.first_name','LIKE', '%'.request('searchTopHealthIssues').'%')
                    ->orWhere('users.last_name','LIKE', '%'.request('searchTopHealthIssues').'%');
                })
                ->get();
            }else;

            if(request()->has(['field','direction']) && request('field') != null && request('direction') != null){
                $query->orderBy(request('field'),request('direction'));
            }

            
            if(in_array("Admin", $this->GetRoles(), true) || in_array("ReportsUser", $this->GetRoles(), true)){
                return Inertia::render('Reports/ReportsHealthVisit', [
                    'Diagnosis' => $query
                    ->where('status', 'OP')
                    ->orderBy('assessment', 'asc')
                    ->orderBy('visit_date', 'desc')
                    ->paginate(25)
                    ->setPath('')   //for prod ip to url handling
                    ->appends($request->query()),
                    'UsersDetails' => User::where('id',Auth::id())->get(),
                    'Filters' => request()->all(['searchTopHealthIssues','field','direction']),
                    'UserRoles' => $this->GetRoles(),
                ]);
            }else{
                // Get areas
                // Get all Areas of Current User
                $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
                // Loop and push to array
                $UserAdminLevelTwo = [];
                $LevelThree = [];
                foreach ($areas as $key) {
                    array_push($UserAdminLevelTwo, $key->admin_level_two_id);
                }

                $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $UserAdminLevelTwo)->get();
                foreach ($AdminLevelThree as $key) {
                    array_push($LevelThree, $key->id);
                }

                // Get all farmer in LevelThree
                $FarmersArray = [];
                $Farmers = Farmer::whereIn('admin_level_three_id', $LevelThree)->get();
                foreach ($Farmers as $key) {
                    array_push($FarmersArray, $key->id);
                }

                // Get all livestock in area
                $LivestockArray = [];
                $Livestock = Livestock::whereIn('farmer_id', $FarmersArray)->get();
                foreach ($Livestock as $key) {
                    array_push($LivestockArray, $key->id);
                }
                // return $LivestockArray;

                return Inertia::render('Reports/ReportsHealthVisit', [
                    'Diagnosis' => $query
                    ->where('status', 'OP')
                    ->whereIn('livestock_id', $LivestockArray)
                    ->orderBy('assessment', 'asc')
                    ->orderBy('visit_date', 'desc')
                    ->paginate(25)
                    ->setPath('')   //for prod ip to url handling
                    ->appends($request->query()),
                    'UsersDetails' => User::where('id',Auth::id())->get(),
                    'Filters' => request()->all(['searchTopHealthIssues','field','direction']),
                    'UserRoles' => $this->GetRoles(),
                ]);
            }
            
        ob_end_flush();
    }

    public function DownloadTopHealthIssuesList(Request $request)
    {
        // return $request;
        $dateFrom = new DateTime($request->dateFrom);
        $dateFromYMD = $dateFrom->format('Y-m-d');
        $dateTo = new DateTime($request->dateTo);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');
        // Get areas
        // Get all Areas of Current User
        $areas = UserAdminLevelTwo::where('user_id', Auth::user()->id)->get();
        // Loop and push to array
        $UserAdminLevelTwo = [];
        $LevelThree = [];
        foreach ($areas as $key) {
            array_push($UserAdminLevelTwo, $key->admin_level_two_id);
        }

        $AdminLevelThree = AdminLevelThree::whereIn('admin_level_two_id', $UserAdminLevelTwo)->get();
        foreach ($AdminLevelThree as $key) {
            array_push($LevelThree, $key->id);
        }

        // Get all farmer in LevelThree
        $FarmersArray = []; 
        $Farmers = Farmer::whereIn('admin_level_three_id', $LevelThree)->get();
        foreach ($Farmers as $key) {
            array_push($FarmersArray, $key->id);
        }

        // Get all livestock in area
        $LivestockArray = [];
        $Livestock = Livestock::whereIn('farmer_id', $FarmersArray)->get();
        foreach ($Livestock as $key) {
            array_push($LivestockArray, $key->id);
        }

        return Excel::download(new TopHealthIssuesMultiSheetExport($LivestockArray, $request->dateFrom, $request->dateTo, $this->GetRoles()), 'Top_Health_Issues_List.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        
    }

}
