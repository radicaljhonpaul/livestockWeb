<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaDiagnosisLog extends Model
{
    use HasFactory;

    protected $table = 'media_diagnosis_logs';

    protected $primaryKey = 'id';

    protected $fillable = ['diagnosis_log_id', 'path_name', 'type'];

    public function diagnosisLog(){
        return $this->belongsTo(DiagnosisLog::class);
    }


}
