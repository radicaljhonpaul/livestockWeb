<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutrientDetail extends Model
{
    use HasFactory;

    protected $table = 'nutrient_details';

    protected $primaryKey = 'id';

    protected $fillable = ['feeding_log_id', 'type', 'dm', 'me', 'cp', 'ndf', 'tdn', 'ca', 'p'];

    

}
