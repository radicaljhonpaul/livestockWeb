<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MediaHealthCondition extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'media_health_conditions';

    protected $primaryKey = 'id';

    

}
