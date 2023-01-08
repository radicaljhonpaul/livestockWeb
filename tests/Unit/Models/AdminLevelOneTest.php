<?php

namespace Tests\Unit\Models;

use App\Models\AdminLevelOne;
use App\Models\AdminLevelTwo;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLevelOneTest extends TestCase
{

    use RefreshDatabase;

    public function test_adminlevelone_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('admin_level_ones','id'), 1);
        $this->assertTrue(Schema::hasColumn('admin_level_ones','name'), 1);

    }

    
    public function test_adminlevelone_has_many_adminleveltwos(){

        $adminOne =  AdminLevelOne::factory()->create();
        $adminTwoa = AdminLevelTwo::factory()->create(['admin_level_one_id' => $adminOne->id]);
        $adminTwob =  AdminLevelTwo::factory()->create(['admin_level_one_id' => $adminOne->id]);
        
        $this->assertTrue($adminOne->adminLevelTwos->contains($adminTwoa));

        $this->assertEquals(2, $adminOne->adminLevelTwos->count());

    }


}
