<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisLogSymptom extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_log_symptoms';

    protected $primaryKey = 'id';

    protected $fillable = ['diagnosis_log_id','symptom_id'];
}
