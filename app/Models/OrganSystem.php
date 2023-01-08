<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrganSystem extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'organ_systems';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'local_term'];

    
    /**
     *  Organ System can have several health conditions 
     * (eg. Organ System: Eye has the ff health conditions: Thelaziasis, Pinkeye)
    **/
    public function healthConditions(){
        return $this->hasMany(HealthCondition::class);
    }

    /**
     *  Organ System can have multiple symptoms associated to it
     *  (eg. Reproduction: Abortion, Abortion 2nd trimester, Enlargement/swelling of testicles / scrotal sac, etc)
     **/
    public function symptoms(){
        return $this->belongsToMany(Symptom::class, 'organ_system_symptom');
    }
    

}
