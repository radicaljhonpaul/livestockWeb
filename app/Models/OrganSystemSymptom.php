<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrganSystemSymptom extends Model
{
    use HasFactory;
    use HasApiTokens;
    
    protected $table = 'organ_system_symptom';
    protected $primaryKey = 'id';
    protected $fillable = ['organ_system_id', 'symptom_id'];

    /**
     *  OrgansSystemSymptom linked to OrgansSystem
    **/
    public function organSystems(){
        return $this->hasMany(OrganSystem::class, 'id', 'organ_system_id');
    }

    public function symptoms(){
        return $this->hasMany(Symptom::class, 'id', 'symptom_id');
    }

}
