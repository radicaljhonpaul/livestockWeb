<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisLogHealthCondition extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_log_health_conditions';

    protected $primaryKey = 'id';
    
    protected $fillable = ['diagnosis_log_id','health_condition_id'];
}
