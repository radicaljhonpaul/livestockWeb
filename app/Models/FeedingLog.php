<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingLog extends Model
{
    use HasFactory;

    protected $table = 'feeding_logs';

    protected $primaryKey = 'id';

    /**
     * Feeding Log belong to a Livestock.
     */
    public function livestock(){
        return $this->belongsTo(Livestock::class);

    }  

    /**
     * Feeding Log Record has 1 corresponding visit log (generic log)
     */
    public function visitLog(){
        return $this->hasOne(VisitLog::class);
    }

     /**
     * Feeding Log one created By
     */
    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }   

    /**
     * Feeding Log can have multiple ingredients via pivot
     */
    
    public function ingredients(){
    
        return $this->belongsToMany(Ingredient::class, 'feeding_log_ingredients')->withPivot('quantity', 'price_at_date');

    }

    public function feedingLogIngredients(){

        return $this->hasMany(FeedingLogIngredient::class);

    }

    public function nutrientDetails(){

        return $this->hasMany(NutrientDetail::class)->orderBy('type', 'desc');
    
    }


}
