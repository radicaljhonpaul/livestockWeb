<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;



class DashboardsController extends Controller
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

    public function AdminDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/Admin', [
                'UserRoles' => $this->GetRoles(),
                // 'UserRoles' => UserRoles::where('user_id',Auth::id())->get(),
            ]);
        ob_end_flush();
    }

    public function VetDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/Vet', [
                'UserRoles' => $this->GetRoles(),

            ]);
        ob_end_flush();
    }

    public function VetAideDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/VetAide', [
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    public function IHealthContentManagerDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/IHealthContentManager', [
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    public function IFodderContentManagerDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/IFodderContentManager', [
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }

    public function ReportsUserDashboard(Request $request)
    {
        ob_start('ob_gzhandler');
            return Inertia::render('Dashboards/ReportsUser', [
                'UserRoles' => $this->GetRoles(),
            ]);
        ob_end_flush();
    }
}
