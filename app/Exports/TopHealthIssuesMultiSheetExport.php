<?php

namespace App\Exports;

use App\Models\DiagnosisLog;
use DateInterval;
use DateTime;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TopHealthIssuesMultiSheetExport implements WithMultipleSheets
{
    use Exportable;

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


    public function sheets(): array{

        $dateFrom = new DateTime($this->DateFrom);
        $dateFromYMD = $dateFrom->format('Y-m-d');
        // syncFromDate
        // syncToDate
        $dateTo = new DateTime($this->DateTo);
        $dateTo->add(new DateInterval('P1D'));
        $add1Day = $dateTo->format('Y-m-d');

        $sheets = [];
        // Get all livestock in area
        $diagnosis_log_id_array = [];
        $DiagnosisLog = DiagnosisLog::whereIn('livestock_id', $this->LivestockArray)
        ->whereBetween("visit_date",
                [
                    $dateFromYMD,
                    $add1Day
                ]
        )
        ->get();

        foreach ($DiagnosisLog as $key) {
            array_push($diagnosis_log_id_array, $key->id);
        }

        $sheets[] = new TopHealthIssuesExport($this->LivestockArray, $this->DateFrom, $this->DateTo, $this->role);
        $sheets[] = new TopHealthIssuesHealthConditionExport($diagnosis_log_id_array);
        $sheets[] = new TopHealthIssuesInterventionsExport($diagnosis_log_id_array);
        $sheets[] = new TopHealthIssuesInterventionsByVetExport($diagnosis_log_id_array);
        $sheets[] = new TopHealthIssuesSymptomsExport($diagnosis_log_id_array);
        
        return $sheets;

    }
}
