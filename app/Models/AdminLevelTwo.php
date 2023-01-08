<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLevelTwo extends Model
{
    use HasFactory;

    protected $table = 'admin_level_twos';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    /**
     * Admin Level 2 (eg. District) belongs to a parent Admin Level One (eg. Region)
     */
    public function adminLevelOne(){
        return $this->belongsTo(AdminLevelOne::class);
    }

    /**
     * Admin Level 2 (eg. District) has many Admin Level 3 
     */
    public function adminLevelThrees(){
        return $this->hasMany(AdminLevelThree::class);
    }

    /**Admin Level two can have multiple users associated to it
     * (eg. Municipality B can have user a, user c)
     */
    public function users(){
        return $this->belongsToMany(User::class, 'user_admin_level_twos');
    }
    

}
