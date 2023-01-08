<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Ingredient;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use RefreshDatabase;

    public function test_categories_has_expected_columns()
    {

        $this->assertTrue(Schema::hasColumn('categories','id'), 1);
        $this->assertTrue(Schema::hasColumn('categories','name'), 1);
        $this->assertTrue(Schema::hasColumn('categories','system_name'), 1);
        $this->assertTrue(Schema::hasColumn('categories','display_order'), 1);

    }

    public function test_category_has_many_ingredients(){

        $category =  Category::factory()->create();
        $ingredient = Ingredient::factory()->create(['category_id' => $category->id]);
        $ingredient2 =  Ingredient::factory()->create(['category_id' => $category->id]);
        

        //Ingredient existins in the category's ingredient collection
        $this->assertTrue($category->ingredients->contains($ingredient));

        //Many ingredients under category
        $this->assertEquals(2, $category->ingredients->count());

    }


}
