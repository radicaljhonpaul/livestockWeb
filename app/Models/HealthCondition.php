<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class HealthCondition extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'health_conditions';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'local_term', 'common_in_region', 
                           'description', 'how_to_diganose', 'treatment', 'advice_to_farmer',
                           'preventive_measure', 'quick_action'];


    /**
     * Health Condition belongs to a parent Organ System 
     * (eg. Health Condtion: Eyeworm infection belongs to Organ System: Eye)
    **/
    public function organSystem(){
        return $this->belongsTo(OrganSystem::class);

    }


    /**
     * Health Condition can have one or more Interventions
     * (eg. Health Condition Calf scours has the interventions: 
     *      a. Collect fecal samples
     *      b. Isolate affected calf with the dam)  
     **/                       
    public function hcInterventions(){
        return $this->hasMany(Intervention::class);
    } 


    /**
     * Health Condition can have one or more symptoms
     * (eg. Health Condition: Bloat has the ff symptoms: 
     *      a. Frothy mouth
     *      b. Frothy salivation
     *      c. Recumbency)
     */
    public function symptoms(){
        return $this->belongsToMany(Symptom::class, 'hc_symptoms')->withPivot('differential', 'id');
    }


    /**
     *  HealthCondition can have multiple media (img/video) attached to it
    **/
    public function mediaHealthCondition(){
        return $this->hasMany(MediaHealthCondition::class);
    }


}
