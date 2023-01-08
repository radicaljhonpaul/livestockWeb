<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitRating extends Model
{
    use HasFactory;

    protected $table = 'visit_ratings';

    protected $primaryKey = 'id';

      /**
     * Diagnosis Log Record has 1 corresponding visit log (generic log)
     */
    public function diagnosisLog(){
        return $this->belongsTo(DiagnosisLog::class);
    }

    public function assignedTo(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }


    public function farmer(){
        return $this->belongsTo(Farmer::class);
    }

    public function livestock(){
        return $this->belongsTo(Livestock::class);
    }

    public function feedingLog(){
        return $this->belongsTo(FeedingLog::class);
    }

}
