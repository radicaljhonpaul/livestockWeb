<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Intervention extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'interventions';

    protected $primaryKey = 'id';

    protected $fillable = ['description', 'need_license'];


    /**
     * Intervention belongs to a parent Health Conidition
     * (eg. 'Isolate affected calf with the dam' is 1 intervention for
     *      Health Condition: Calf scours 
     **/   
    public function healthCondition(){
    
        return $this->belongsTo(HealthCondition::class);

    }

}
