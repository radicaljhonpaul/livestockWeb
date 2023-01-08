<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\SrpYear;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SrpYearTest extends TestCase
{

    use RefreshDatabase;

    public function test_srp_year_has_expected_columns()
    {
        
        $this->assertTrue(Schema::hasColumn('srp_years','id'), 1);
        $this->assertTrue(Schema::hasColumn('srp_years','year'), 1);

    }
    

    public function test_srpyear_belongs_to_many_ingredients(){

        $ingredient = Ingredient::factory()->create();
        $srp_year = SrpYear::factory()->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $srp_year->ingredients);

    }
       
    


}
