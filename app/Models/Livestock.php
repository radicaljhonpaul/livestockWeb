<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
    use HasFactory;

    protected $table = 'livestocks';

    protected $primaryKey = 'id';

    protected $fillable = ['carabao_code', 'breed', 'sex', 'year_of_birth', 'registration_date'];

    /**
     * Livestock belongs to Farmer
     */
    public function farmer(){
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Livestock has several pregnancies
     */
    public function pregnancies(){
        return $this->hasMany(Pregnancy::class);
    }

    /**
     * Livestock has many Diagnosis Logs
     */
    public function diagnosisLogs(){
        return $this->hasMany(DiagnosisLog::class);
    }

    /**
     * Livestock has many visit log recordings
     */
    public function visitLogs(){
        return $this->hasMany(VisitLog::class);
    }

    /**
     * Livestock has many Feeding Logs
     */
    public function feedingLogs(){
        return $this->hasMany(FeedingLog::class);
    }


}
