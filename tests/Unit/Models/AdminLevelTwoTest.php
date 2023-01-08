<?php

namespace Tests\Unit\Models;

use App\Models\AdminLevelOne;
use App\Models\AdminLevelTwo;
use App\Models\AdminLevelThree;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLevelTwoTest extends TestCase
{

    use RefreshDatabase;

    public function test_adminleveltwo_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('admin_level_twos','id'), 1);
        $this->assertTrue(Schema::hasColumn('admin_level_twos','name'), 1);
        $this->assertTrue(Schema::hasColumn('admin_level_twos','admin_level_one_id'), 1);

    }

    public function test_adminleveltwo_belongs_to_adminlevelone(){

        $adminOne =  AdminLevelOne::factory()->create();
        $adminTwo = AdminLevelTwo::factory()->create(['admin_level_one_id' => $adminOne->id]);


        $this->assertInstanceOf(AdminLevelOne::class, $adminTwo->adminLevelOne);

    }    

    
    public function test_adminleveltwo_has_many_adminlevelthrees(){

        $adminTwo =  AdminLevelTwo::factory()->create();
        $adminThreea = AdminLevelThree::factory()->create(['admin_level_two_id' => $adminTwo->id]);
        $adminThreeb =  AdminLevelThree::factory()->create(['admin_level_two_id' => $adminTwo->id]);
        
        $this->assertTrue($adminTwo->adminLevelThrees->contains($adminThreea));

        $this->assertEquals(2, $adminTwo->adminLevelThrees->count());

    }
    


}
