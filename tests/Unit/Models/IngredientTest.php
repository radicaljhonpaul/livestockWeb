<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\SrpYear;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IngredientTest extends TestCase
{

    use RefreshDatabase;

    public function test_ingredients_has_expected_columns()
    {
        
        $this->assertTrue(Schema::hasColumn('ingredients','id'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','name'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','dm'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','me'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','cp'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','ndf'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','tdn'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','ca'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','p'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','min'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','max'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','category_id'), 1);
        $this->assertTrue(Schema::hasColumn('ingredients','preload'), 1);

    }
    
    public function test_ingredients_belongs_to_category(){

        $category =  Category::factory()->create();
        $ingredient = Ingredient::factory()->create(['category_id' => $category->id]);


        $this->assertInstanceOf(Category::class, $ingredient->category);

    }


    public function test_ingredients_belongs_to_many_srpyear(){

        $ingredient = Ingredient::factory()->create();
        $srp_year = SrpYear::factory()->create();


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $ingredient->srpYears);

    }
       
    


}
