<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class HcSymptom extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'hc_symptoms';

    protected $primaryKey = 'id';

    protected $fillable = ['differential'];

    protected $visible = ['symptom_id', 'health_condition_id', 'id', 'differential'];
    
}
