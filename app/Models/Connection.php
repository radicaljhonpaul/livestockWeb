<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Connection extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'connections';    

    protected $fillable = [
        'name',
        'description',
    ];




}
