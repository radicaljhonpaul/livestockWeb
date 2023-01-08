<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MediaSymptom extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'media_symptoms';

    protected $primaryKey = 'id';

}
