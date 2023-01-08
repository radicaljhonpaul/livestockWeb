<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisLog extends Model
{
    use HasFactory;
    
    protected $table = 'diagnosis_logs';

    protected $primaryKey = 'id';

    protected $fillable = ['visit_date', 'activity_type', 'status', 'assessment', 'notes'];

    /**
     * Diagnosis Log belong to a Livestock.
     */
    public function livestock(){
        return $this->belongsTo(Livestock::class);

    }  

    /**
     * Diagnosis Log Record has 1 corresponding visit log (generic log)
     */
    public function visitLog(){
        return $this->hasOne(VisitLog::class);
    }

    /**
     *  DiagnosisLog can have multiple media (img/video) attached to it
    **/
    public function mediaDiagnosisLogs(){
        return $this->hasMany(MediaDiagnosisLog::class);
    }

    /**
     *  DiagnosisLog can have one or many interventions applied during visit
     *  eg. a. Collect fecal samples
     *      b. Isolate affected calf with the dam
    **/
    public function interventions(){
        return $this->belongsToMany(Intervention::class, 'diagnosis_log_interventions')
        ->join('diagnosis_logs', 'diagnosis_logs.id', '=', 'diagnosis_log_interventions.diagnosis_log_id')
            ->select('interventions.*')
            ->withPivot('diagnosis_logs.external_id');
    }


    /**
     * DiagnosisLog can have one or more symptoms present / seen during visit
     *      a. Frothy mouth
     *      b. Recumbency)
     */
    public function symptoms(){
        return $this->belongsToMany(Symptom::class, 'diagnosis_log_symptoms')
            ->join('diagnosis_logs', 'diagnosis_logs.id', '=', 'diagnosis_log_symptoms.diagnosis_log_id')
            ->select('symptoms.*')
            ->withPivot('diagnosis_logs.external_id');
    }
    
    /**
     * DiagnosisLog can have one or more health conditions identified during visit
     */
    public function healthConditions(){
        return $this->belongsToMany(HealthCondition::class, 'diagnosis_log_health_conditions')
            ->join('diagnosis_logs', 'diagnosis_logs.id', '=', 'diagnosis_log_health_conditions.diagnosis_log_id')
                ->select('health_conditions.*')
                ->withPivot('diagnosis_logs.external_id');
    }

    /**
     * DiagnosisLog one assigned to
     */
    public function assignedTo(){
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }

    /**
     * DiagnosisLog one assigned to
     */
    public function authorizedBy(){
        return $this->hasOne(User::class, 'id', 'authorized_by');
    }

    /**Added for API Request
     * Other Option for pulling related symptoms with diagnosis_log info - uncomment if needed

    public function diagnosisLogSymptoms(){
        return $this->hasMany(DiagnosisLogSymptom::class)
                  ->join('diagnosis_logs', 'diagnosis_logs.id', '=', 'diagnosis_log_id')
                  ->select('diagnosis_log_symptoms.*', 'diagnosis_logs.external_id');
    }

    **/

}
