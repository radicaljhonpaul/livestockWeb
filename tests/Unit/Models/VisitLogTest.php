<?php

namespace Tests\Unit\Models;

use App\Models\Livestock;
use App\Models\Farmer;
use App\Models\User;
use App\Models\VisitLog;
use App\Models\DiagnosisLog;
use App\Models\FeedingLog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VisitLogTest extends TestCase
{

    use RefreshDatabase;

    public function test_visit_logs_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('visit_logs','id'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','visit_date'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','farmer_id'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','livestock_id'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','diagnosis_log_id'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','feeding_log_id'), 1);
        $this->assertTrue(Schema::hasColumn('visit_logs','assigned_to'), 1);

    }

    public function test_visit_log_belongs_to_diagnosis_log(){

        $diagnosisLog =  DiagnosisLog::factory()->create();
        $visitLog = visitLog::factory()->create(['diagnosis_log_id' => $diagnosisLog->id]);

        $this->assertInstanceOf(DiagnosisLog::class, $visitLog->diagnosisLog);

    }    

    public function test_visit_log_belongs_to_user(){

        $user =  User::factory()->create();
        $visitLog = visitLog::factory()->create(['assigned_to' => $user->id]);

        $this->assertInstanceOf(User::class, $visitLog->assignedTo);

    }     

    public function test_visit_log_belongs_to_farmer(){

        $farmer =  Farmer::factory()->create();
        $visitLog = VisitLog::factory()->create(['farmer_id' => $farmer->id]);

        $this->assertInstanceOf(Farmer::class, $visitLog->farmer);

    }     

    public function test_visit_log_belongs_to_livestock(){

        $livestock =  Livestock::factory()->create();
        $visitLog = VisitLog::factory()->create(['livestock_id' => $livestock->id]);

        $this->assertInstanceOf(Livestock::class, $visitLog->livestock);

    }        

    //Additional custom tests for User Model in relation to visit Logs

    public function test_user_has_many_visit_logs(){

        $user =  User::factory()->create();
        $vistLogA = VisitLog::factory()->create(['assigned_to' => $user->id]);
        $vistLogB =  VisitLog::factory()->create(['assigned_to' => $user->id]);
    
        $this->assertTrue($user->visitLogs->contains($vistLogA));

        $this->assertEquals(2, $user->visitLogs->count());

    }


    public function test_visit_log_belongs_to_feeding_log(){

        $feedingLog =  FeedingLog::factory()->create();
        $visitLog = visitLog::factory()->create(['feeding_log_id' => $feedingLog->id]);

        $this->assertInstanceOf(FeedingLog::class, $visitLog->feedingLog);

    }    



}
