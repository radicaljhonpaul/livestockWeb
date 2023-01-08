<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingLogIngredient extends Model
{
    use HasFactory;

    protected $table = 'feeding_log_ingredients';

    protected $primaryKey = 'id';

    protected $fillable = ['feeding_log_id', 'ingredient_id', 'quantity', 'price_at_date'];



}
