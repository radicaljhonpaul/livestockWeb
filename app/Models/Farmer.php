<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $table = 'farmers';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'last_name', 'first_name', 'gender', 'pcc_system_id', 'gender', 'birthdate', 'mobile_number', 'phone_number', 'fb_profile', 'lat', 'long'];

    /**
     * Farmer belongs to a parent Admin Level Three (eg. Brgy)
     */
    public function adminLevelThree(){
        return $this->belongsTo(AdminLevelThree::class);
    }

    /**
     * Farmer can have one or more Livestocks they manage
     */
    public function livestocks(){
        return $this->hasMany(Livestock::class);
    }   

    /**
     * Farmer can have one or more logs of visit interactions from Extension Officers
     */
    public function visitLogs(){
        return $this->hasMany(VisitLog::class);
    } 

    /**
     * Farmer can have one district
     */
    public function admin_level_three(){
        return $this->hasOne(AdminLevelThree::class, 'id', 'admin_level_three_id');
    } 
}
