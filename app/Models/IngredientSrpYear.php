<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientSrpYear extends Model
{
    use HasFactory;

    protected $table = 'ingredient_srp_year';

    protected $primaryKey = 'id';
   
    protected $visible = ['ingredient_id', 'srp_year_id', 'price'];

    protected $fillable = ['ingredient_id', 'srp_year_id', 'price'];

}
