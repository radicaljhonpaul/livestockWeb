<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Symptom extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'symptoms';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'local_term'];

    /**
     *  Symtpom can belong from 1 to many Organ System
     * (eg. Abortion is a symptom of Reproductive System, Gastrointentestinal, Nervous)
    **/
    public function organSystems(){
        return $this->belongsToMany(HealthCondition::class, 'hc_symptoms')->withPivot('differential', 'id');
    }

    /**
     *  Symptoms can have multiple media (img/video) attached to it
    **/
    public function mediaSymptoms(){
        return $this->hasMany(MediaSymptom::class);
    }

    /**
     *  Symptoms can have multiple parent symptoms
    **/
    public function organSystemsSymptoms(){
        return $this->hasMany(OrganSystemSymptom::class, 'symptom_id', 'id');
    }

    public function parentSymptom(){
        return $this->hasOne(Symptom::class, 'id', 'parent_symptom');
    }

}
