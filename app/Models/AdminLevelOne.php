<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLevelOne extends Model
{
    use HasFactory;

    protected $table = 'admin_level_ones';

    protected $fillable = ['name'];

    /**
     * Admin Level One (eg. Region) can have several Admin Level Two (eg. Districts)
     */
    public function adminLevelTwos(){
        return $this->hasMany(AdminLevelTwo::class);
    }


}
