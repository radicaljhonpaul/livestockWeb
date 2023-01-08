<?php

namespace Tests\Unit\Models;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\Pregnancy;
use App\Models\VisitLog;
use App\Models\DiagnosisLog;
use App\Models\MediaDiagnosisLog;
use App\Models\Intervention;
use App\Models\Symptom;
use App\Models\User;
use App\Models\HealthCondition;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DiagnosisLogTest extends TestCase
{

    use RefreshDatabase;

    public function test_diagnosis_log_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','id'), 1); 
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','visit_date'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','activity_type'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','status'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','assessment'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','notes'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','created_by'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','authorized_by'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','assigned_to'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','livestock_id'), 1);
        $this->assertTrue(Schema::hasColumn('diagnosis_logs','is_pregnant'), 1);
        //$this->assertTrue(Schema::hasColumn('diagnosis_logs','health_condition_id'), 1);
    }

    
    public function test_diagnosis_log_belongs_livestock(){

        $livestock =  Livestock::factory()->create();
        $diagnosislog = DiagnosisLog::factory()->create(['livestock_id' => $livestock->id]);

        $this->assertInstanceOf(Livestock::class, $diagnosislog->livestock);

    }    

    public function test_diagnosis_log_has_one_visitlog(){

        $diagnosislog = DiagnosisLog::factory()->create();
        $visitlog =  VisitLog::factory()->create(['diagnosis_log_id' => $diagnosislog->id]);

        $this->assertInstanceOf(VisitLog::class, $diagnosislog->visitLog);

    }    

    public function test_diagnosis_belongs_to_many_interventions(){

        $diagnosislog = DiagnosisLog::factory()
        ->has(Intervention::factory()->count(3))   
        ->create();


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $diagnosislog->interventions);
        $this->assertEquals(3, $diagnosislog->interventions->count());

    }  

    public function test_diagnosis_belongs_to_many_symptoms(){

        $diagnosislog = DiagnosisLog::factory()
        ->has(Symptom::factory()->count(3))   
        ->create();


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $diagnosislog->symptoms);
        $this->assertEquals(3, $diagnosislog->symptoms->count());

    }    


    public function test_diagnosis_log_has_many_media_diagnosis_log(){

        $diagnosislog =  DiagnosisLog::factory()->create();
        $mediaA = MediaDiagnosisLog::factory()->create(['diagnosis_log_id' => $diagnosislog->id]);
        $mediaB =  MediaDiagnosisLog::factory()->create(['diagnosis_log_id' => $diagnosislog->id]);
    
        $this->assertTrue($diagnosislog->mediaDiagnosisLogs->contains($mediaA));

        $this->assertEquals(2, $diagnosislog->mediaDiagnosisLogs->count());

    }   

    //Additional custom tests for User Model in relation to Diagnosis Logs

    public function test_user_has_many_diagnosis_logs_assigned_to(){

        $user =  User::factory()->create();
        $diagnoisisLogA = DiagnosisLog::factory()->create(['assigned_to' => $user->id]);
        $diagnoisisLogA =  DiagnosisLog::factory()->create(['assigned_to' => $user->id]);
    
        $this->assertTrue($user->diagnosisLogsAssignedTos->contains($diagnoisisLogA));

        $this->assertEquals(2, $user->diagnosisLogsAssignedTos->count());

    }


    public function test_user_has_many_diagnosis_logs_created_by(){

        $user =  User::factory()->create();
        $diagnoisisLogA = DiagnosisLog::factory()->create(['created_by' => $user->id]);
        $diagnoisisLogA =  DiagnosisLog::factory()->create(['created_by' => $user->id]);
    
        $this->assertTrue($user->diagnosisLogsCreatedBys->contains($diagnoisisLogA));

        $this->assertEquals(2, $user->diagnosisLogsCreatedBys->count());

    }   


    public function test_user_has_many_diagnosis_logs_authorized_by(){

        $user =  User::factory()->create();
        $diagnoisisLogA = DiagnosisLog::factory()->create(['authorized_by' => $user->id]);
        $diagnoisisLogA =  DiagnosisLog::factory()->create(['authorized_by' => $user->id]);
    
        $this->assertTrue($user->diagnosisLogsAuthorizedBys->contains($diagnoisisLogA));

        $this->assertEquals(2, $user->diagnosisLogsAuthorizedBys->count());

    }   


    public function test_diagnosis_belongs_to_many_health_conditions(){

        $diagnosislog = DiagnosisLog::factory()
        ->has(HealthCondition::factory()->count(3))   
        ->create();


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $diagnosislog->healthConditions);
        $this->assertEquals(3, $diagnosislog->healthConditions->count());

    } 


}
