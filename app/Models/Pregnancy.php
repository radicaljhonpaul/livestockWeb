<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    use HasFactory;

    protected $table = 'pregnancies';

    protected $primaryKey = 'id';

    protected $fillable = ['start_date', 'end_date', 'created_by', 'livestock_id'];

    /**
     * Pregnancy records belong to a Livestock.
     * A Livestock can have several pregnancies.
     */
    public function livestock(){
        return $this->belongsTo(Livestock::class);

    }  

    

}
