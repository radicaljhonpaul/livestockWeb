<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrpYear extends Model
{
    use HasFactory;

    protected $table = 'srp_years';

    protected $primaryKey = 'id';

    protected $fillable = ['year'];

    /**
     * SRP Years can have many ingredients & pricing
     **/   
    public function ingredients(){
    
        return $this->belongsToMany(Ingredient::class, 'ingredient_srp_year')->withPivot('price');

    }

}
