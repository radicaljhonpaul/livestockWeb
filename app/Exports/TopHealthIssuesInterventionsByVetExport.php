<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogIntervention;
use Illuminate\Support\Facades\DB;

class TopHealthIssuesInterventionsByVetExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    private $sheetTitle = " Interventions By Vet Report";

    public function __construct(array $diagnosis_log_id_array)
    {
        $this->diagnosis_log_id_array = $diagnosis_log_id_array;
    }

    public function setParams(array $diagnosis_log_id_array)
    {
        $this->diagnosis_log_id_array = $diagnosis_log_id_array;
    }

    public function collection()
    {
        return DiagnosisLogIntervention::join('interventions', 'diagnosis_log_interventions.intervention_id', '=', 'interventions.id')
        ->join('diagnosis_logs', 'diagnosis_log_interventions.diagnosis_log_id', '=', 'diagnosis_logs.id')
        ->join('health_conditions as joined_health_conditions', 'interventions.health_condition_id', '=', 'joined_health_conditions.id')
        ->join('livestocks as joined_livestocks', 'diagnosis_logs.livestock_id', '=', 'joined_livestocks.id')
        ->join('farmers', 'joined_livestocks.farmer_id', '=', 'farmers.id')
        ->join('admin_level_threes', 'admin_level_threes.id', '=', 'farmers.admin_level_three_id')
        ->join('admin_level_twos', 'admin_level_twos.id', '=', 'admin_level_threes.admin_level_two_id')
        ->join('admin_level_ones', 'admin_level_ones.id', '=', 'admin_level_twos.admin_level_one_id')
        ->where('interventions.need_license', 1)
        ->whereIn('diagnosis_log_id', $this->diagnosis_log_id_array)
        ->select(
            'visit_date as visitDate',
            'admin_level_ones.name AS prov_name',
            'admin_level_twos.name AS city_name',
            'admin_level_threes.name AS brgy_name',
            'farmers.last_name as joined_farmers_last_name',
            'farmers.first_name as joined_farmers_first_name',
            'farmers.pcc_system_id as joined_farmers_pcc_system_id',
            'joined_livestocks.carabao_code as joined_livestocks_carabao_code',
            'diagnosis_logs.external_id as external_id',
            'joined_health_conditions.name AS joined_health_conditions_interventions_name',
            'interventions.description AS interventions_description',
        )
        ->orderBy('visit_date', 'desc')
        ->orderBy('external_id', 'asc')
        ->get();
    }

    //Customize heading
    public function headings(): array
    {
        return [
            'Date of Visit',
            'Province',
            'Municipality City',
            'Baranggay',
            'Last Name Farmer',
            'First Name Farmer',
            'Farmer Code',
            'Carabao Code',
            'Diagnosis Log External Id',
            'Health Condition',
            'Intervention',
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
