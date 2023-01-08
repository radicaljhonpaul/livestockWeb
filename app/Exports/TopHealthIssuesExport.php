<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\DiagnosisLog;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class TopHealthIssuesExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    private $sheetTitle = "Top Health Issues Report";
    
    public function __construct(array $LivestockArray, string $DateFrom,  string $DateTo, array $role)
    {
        $this->LivestockArray = $LivestockArray;
        $this->DateFrom = $DateFrom;
        $this->DateTo = $DateTo;
        $this->role = $role;
    }

    public function setParams(array $LivestockArray, string $DateFrom,  string $DateTo, array $role)
    {
        $this->LivestockArray = $LivestockArray;
        $this->DateFrom = $DateFrom;
        $this->DateTo = $DateTo;
        $this->role = $role;
    }

    public function collection()
    {

        $dateFrom = new DateTime($this->DateFrom);
        $dateFromYMD = $dateFrom->format('Y-m-d');
        // syncFromDate
        // syncToDate
        $dateTo = new DateTime($this->DateTo);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');

        if(in_array("Admin", $this->role, true) || in_array("ReportsUser", $this->role, true)){
            return DiagnosisLog::join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
            ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
            ->join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
            ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
            ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
            ->join('users as assignedTo', 'diagnosis_logs.assigned_to', '=', 'assignedTo.id')
            ->leftJoin('users as authorizeBy', 'diagnosis_logs.authorized_by', '=', 'authorizeBy.id')
            ->select(
                'visit_date',
                'admin_level_ones.name AS prov_name',
                'admin_level_twos.name AS city_name',
                'admin_level_threes.name AS brgy_name',
                DB::raw("CONCAT(assignedTo.first_name,' ',assignedTo.last_name) as staff_full_name"),
                'farmers.last_name as joined_farmers_last_name',
                'farmers.first_name as joined_farmers_first_name',
                'farmers.pcc_system_id as joined_farmers_pcc_system_id',
                'joined_livestocks.carabao_code as joined_livestocks_carabao_code',
                'assessment as diagnosisiAssessment',
                'activity_type as diagnosisiActivityType',
                DB::raw("CONCAT(authorizeBy.last_name,', ',authorizeBy.first_name) as authorizeBystaff_full_name"),
                'notes',
                'diagnosis_logs.is_pregnant as is_pregnant',
                'diagnosis_logs.created_at as created_at_date',
                'diagnosis_logs.updated_at as updated_at_date',
                'diagnosis_logs.external_id as external_id',
            )
            ->whereBetween("visit_date",
                    [
                        $dateFromYMD,
                        $add1Day
                    ]
            )
            ->orderBy('visit_date', 'desc')
            ->orderBy('external_id', 'asc')
            ->orderBy('assessment', 'asc')
            ->get()
            ->map(function($diagnosis_logs){
                $diagnosis_logs->is_pregnant = str_replace('1', 'Yes', $diagnosis_logs->is_pregnant);
                $diagnosis_logs->is_pregnant = str_replace('0', 'No', $diagnosis_logs->is_pregnant);
                return $diagnosis_logs;
            });
        
        }else{

            return DiagnosisLog::join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
            ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
            ->join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
            ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
            ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
            ->join('users as assignedTo', 'diagnosis_logs.assigned_to', '=', 'assignedTo.id')
            ->leftJoin('users as authorizeBy', 'diagnosis_logs.authorized_by', '=', 'authorizeBy.id')
            ->select(
                'visit_date as visitDate',
                'admin_level_ones.name AS prov_name',
                'admin_level_twos.name AS city_name',
                'admin_level_threes.name AS brgy_name',
                DB::raw("CONCAT(assignedTo.first_name,' ',assignedTo.last_name) as staff_full_name"),
                'farmers.last_name as joined_farmers_last_name',
                'farmers.first_name as joined_farmers_first_name',
                'farmers.pcc_system_id as joined_farmers_pcc_system_id',
                'joined_livestocks.carabao_code as joined_livestocks_carabao_code',
                'assessment as diagnosisiAssessment',
                'activity_type as diagnosisiActivityType',
                DB::raw("CONCAT(authorizeBy.last_name,' ',authorizeBy.first_name) as authorizeBystaff_full_name"),
                'notes as notes',
                'diagnosis_logs.is_pregnant as is_pregnant',
                'diagnosis_logs.created_at as created_at_date',
                'diagnosis_logs.updated_at as updated_at_date',
                'diagnosis_logs.external_id as external_id',
            )
            ->whereIn('livestock_id', $this->LivestockArray)
            ->whereBetween("visit_date",
                    [
                        $dateFromYMD,
                        $add1Day
                    ]
            )
            ->orderBy('visit_date', 'desc')
            ->orderBy('external_id', 'asc')
            ->orderBy('assessment', 'asc')
            ->get()
            ->map(function($diagnosis_logs){
                $diagnosis_logs->is_pregnant = str_replace('1', 'Yes', $diagnosis_logs->is_pregnant);
                $diagnosis_logs->is_pregnant = str_replace('0', 'No', $diagnosis_logs->is_pregnant);
                return $diagnosis_logs;
            });
        }
    }

    //Customize heading
    public function headings(): array
    {
        return [
            'Date of Visit',
            'Province',
            'Municipality City',
            'Baranggay',
            'Staff Name',
            'Last Name Farmer',
            'First Name Farmer',
            'Farmer Code',
            'Carabao Code',
            'Assessment',
            'Activity Type',
            'Authorization By',
            'Notes',
            'Carabao Pregnant During Visit',
            'Created Date',
            'Modified Date',
            'Diagnosis Log External Id',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheetTitle;
    }

}