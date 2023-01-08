<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientSrpYear;
use App\Models\SrpYear;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IngredientSrpYearTest extends TestCase
{

    use RefreshDatabase;

    public function test_pivot_ingredient_srp_year_has_expected_columns()
    {
        
        $this->assertTrue(Schema::hasColumn('ingredient_srp_year','id'), 1);
        $this->assertTrue(Schema::hasColumn('ingredient_srp_year','ingredient_id'), 1);
        $this->assertTrue(Schema::hasColumn('ingredient_srp_year','srp_year_id'), 1);
        $this->assertTrue(Schema::hasColumn('ingredient_srp_year','price'), 1);

    }
    
       

}
