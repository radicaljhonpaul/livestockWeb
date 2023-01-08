<?php

namespace Tests\Unit\Models;

use App\Models\Livestock;
use App\Models\Pregnancy;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PregnancyTest extends TestCase
{

    use RefreshDatabase;

    public function test_prenancy_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('pregnancies','id'), 1);
        $this->assertTrue(Schema::hasColumn('pregnancies','start_date'), 1);
        $this->assertTrue(Schema::hasColumn('pregnancies','end_date'), 1);
        $this->assertTrue(Schema::hasColumn('pregnancies','livestock_id'), 1);
        $this->assertTrue(Schema::hasColumn('pregnancies','created_by'), 1);

    }

    public function test_pregnancy_belongs_to_livestock(){

        $livestock =  Livestock::factory()->create();
        $pregnancy = Pregnancy::factory()->create(['livestock_id' => $livestock->id]);


        $this->assertInstanceOf(Livestock::class, $pregnancy->livestock);

    }    


}
