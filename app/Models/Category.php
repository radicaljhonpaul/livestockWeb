<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    /**
     *  Feed Category can have several ingredients 
     * (eg. Grasses has: Napier, Rice Bran)
    **/
    public function ingredients(){
        return $this->hasMany(Ingredient::class);
    }
}
