<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'dm', 'me', 'cp', 'ndf', 'tdn', 'ca', 'p', 'min', 'max', 'category_id', 'preload'];


    /**
     * Ingredient belongs to a category
     **/   
    public function category(){
    
        return $this->belongsTo(Category::class);

    }

    /**
     * Ingredient has Many SRP Years
     **/   
    public function srpYears(){
    
        return $this->belongsToMany(SrpYear::class, 'ingredient_srp_year')->withPivot('price');

    }


}
