<?php

namespace Tests\Unit\Models;

use App\Models\Farmer;
use App\Models\AdminLevelTwo;
use App\Models\AdminLevelThree;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLevelThreeTest extends TestCase
{

    use RefreshDatabase;

    public function test_adminlevelthree_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('admin_level_threes','id'), 1);
        $this->assertTrue(Schema::hasColumn('admin_level_threes','name'), 1);
        $this->assertTrue(Schema::hasColumn('admin_level_threes','admin_level_two_id'), 1);

    }

    public function test_adminlevelthree_belongs_to_adminleveltwo(){

        $adminTwo =  AdminLevelTwo::factory()->create();
        $adminThree = AdminLevelThree::factory()->create(['admin_level_two_id' => $adminTwo->id]);


        $this->assertInstanceOf(AdminLevelTwo::class, $adminThree->adminLevelTwo);

    }    

    
    public function test_adminlevelthree_has_many_farmers(){

        $adminThree =  AdminLevelThree::factory()->create();
        $farmera = Farmer::factory()->create(['admin_level_three_id' => $adminThree->id]);
        $farmberb =  Farmer::factory()->create(['admin_level_three_id' => $adminThree->id]);
    

        //Farmer existing in the admin level three's farmer collection
        $this->assertTrue($adminThree->farmers->contains($farmera));

        //Many farmers under admin level three
        $this->assertEquals(2, $adminThree->farmers->count());

        

    }
    
    


}
