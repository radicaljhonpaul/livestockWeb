<?php

namespace Tests\Unit\Models;

use App\Models\MediaDiagnosisLog;
use App\Models\DiagnosisLog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MediaDiagnosisLogTest extends TestCase
{

    use RefreshDatabase;

    public function test_media_diagnosis_logs_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('media_diagnosis_logs','id'), 1);
        $this->assertTrue(Schema::hasColumn('media_diagnosis_logs','diagnosis_log_id'), 1);
        $this->assertTrue(Schema::hasColumn('media_diagnosis_logs','path_name'), 1);
        $this->assertTrue(Schema::hasColumn('media_diagnosis_logs','type'), 1);

    }

    public function test_media_diagnosis_logs_belongs_to_diagnosis_logs(){

        $diagnosisLog =  DiagnosisLog::factory()->create();
        $mediaDiagnosisLog =  MediaDiagnosisLog::factory()->create(['diagnosis_log_id' => $diagnosisLog->id]);


        $this->assertInstanceOf(DiagnosisLog::class, $mediaDiagnosisLog->diagnosisLog);

    }    


}
