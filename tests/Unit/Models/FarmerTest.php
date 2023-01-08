<?php

namespace Tests\Unit\Models;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\VisitLog;
use App\Models\AdminLevelThree;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FarmerTest extends TestCase
{

    use RefreshDatabase;

    public function test_farmer_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('farmers','id'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','pcc_system_id'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','last_name'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','first_name'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','gender'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','birthdate'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','mobile_number'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','phone_number'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','fb_profile'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','lat'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','longitude'), 1);
        $this->assertTrue(Schema::hasColumn('farmers','admin_level_three_id'), 1);

    }

    
    public function test_farmer_belongs_to_adminlevelthree(){

        $adminThree =  AdminLevelThree::factory()->create();
        $farmer = Farmer::factory()->create(['admin_level_three_id' => $adminThree->id]);

        $this->assertInstanceOf(AdminLevelThree::class, $farmer->adminLevelThree);

    }    

    
    
    public function test_farmer_has_many_livestocks(){

        $farmer =  Farmer::factory()->create();
        $livestocka = Livestock::factory()->create(['farmer_id' => $farmer->id]);
        $livestockb =  Livestock::factory()->create(['farmer_id' => $farmer->id]);
    
        $this->assertTrue($farmer->livestocks->contains($livestocka));

        $this->assertEquals(2, $farmer->livestocks->count());

    }

    public function test_farmer_has_many_visitlogs(){

        $farmer =  Farmer::factory()->create();
        $visitloga = VisitLog::factory()->create(['farmer_id' => $farmer->id]);
        $visitloga =  VisitLog::factory()->create(['farmer_id' => $farmer->id]);
    
        $this->assertTrue($farmer->visitLogs->contains($visitloga));

        $this->assertEquals(2, $farmer->visitLogs->count());

    }
    


}
