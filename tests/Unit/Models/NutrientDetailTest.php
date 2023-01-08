<?php

namespace Tests\Unit\Models;

use App\Models\NutrientModel;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;

use Tests\TestCase;

class NutrientDetailTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_nutrient_detail_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('nutrient_details','id'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','type'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','dm'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','me'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','cp'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','ndf'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','tdn'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','ca'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','p'), 1);
    }
}
