<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerConsent extends Model
{
    use HasFactory;

    protected $table = 'farmer_consents';

    protected $primaryKey = 'id';

    protected $fillable = ['type', 'method', 'details', 'consent'];


}
