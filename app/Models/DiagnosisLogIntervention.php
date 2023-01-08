<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisLogIntervention extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_log_interventions';

    protected $primaryKey = 'id';

    protected $fillable = ['diagnosis_log_id','intervention_id'];
    
}
