<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLevelThree extends Model
{
    use HasFactory;

    protected $table = 'admin_level_threes';

    protected $fillable = ['name'];

    /**
     * Admin Level 3 (eg. Brgy) belongs to a parent Admin Level Two (eg. District)
     */
    public function adminLevelTwo(){
        return $this->belongsTo(AdminLevelTwo::class);
    } 

    /**
     * Admin Level 3 (eg. Brgy) has many Farmers belonging to this area
     */
    public function farmers(){
        return $this->hasMany(Farmer::class);
    }


}
