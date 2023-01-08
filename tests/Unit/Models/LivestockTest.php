<?php

namespace Tests\Unit\Models;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\Pregnancy;
use App\Models\VisitLog;
use App\Models\DiagnosisLog;
use App\Models\FeedingLog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LivestockTest extends TestCase
{

    use RefreshDatabase;

    public function test_livestock_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('livestocks','id'), 1); 
        $this->assertTrue(Schema::hasColumn('livestocks','carabao_code'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','breed'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','sex'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','year_of_birth'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','registration_date'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','is_pregnant'), 1);
        $this->assertTrue(Schema::hasColumn('livestocks','farmer_id'), 1);

    }

    
    public function test_livestock_belongs_farmer(){

        $farmer =  Farmer::factory()->create();
        $livestock = Livestock::factory()->create(['farmer_id' => $farmer->id]);

        $this->assertInstanceOf(Farmer::class, $livestock->farmer);

    }    

    public function test_livestocks_has_many_pregnancies(){

        $livestock =  Livestock::factory()->create();
        $pregnancya = Pregnancy::factory()->create(['livestock_id' => $livestock->id]);
        $pregnancyb =  Pregnancy::factory()->create(['livestock_id' => $livestock->id]);
    
        $this->assertTrue($livestock->pregnancies->contains($pregnancya));

        $this->assertEquals(2, $livestock->pregnancies->count());

    }

    public function test_livestocks_has_many_diagnosis_logs(){

        $livestock =  Livestock::factory()->create();
        $diagnosisloga = DiagnosisLog::factory()->create(['livestock_id' => $livestock->id]);
        $diagnosislogb =  DiagnosisLog::factory()->create(['livestock_id' => $livestock->id]);
    
        $this->assertTrue($livestock->diagnosisLogs->contains($diagnosisloga));

        $this->assertEquals(2, $livestock->diagnosisLogs->count());

    }   


    public function test_livestocks_has_many_visit_logs(){

        $livestock =  Livestock::factory()->create();
        $visitloga = VisitLog::factory()->create(['livestock_id' => $livestock->id]);
        $visitlogb =  VisitLog::factory()->create(['livestock_id' => $livestock->id]);
    
        $this->assertTrue($livestock->visitLogs->contains($visitloga));

        $this->assertEquals(2, $livestock->visitLogs->count());

    }    

    public function test_livestocks_has_many_feeding_logs(){

        $livestock =  Livestock::factory()->create();
        $feedingloga = FeedingLog::factory()->create(['livestock_id' => $livestock->id]);
        $feedinglogb =  FeedingLog::factory()->create(['livestock_id' => $livestock->id]);
    
        $this->assertTrue($livestock->feedingLogs->contains($feedingloga));

        $this->assertEquals(2, $livestock->feedingLogs->count());

    }   



}
