<?php

namespace Tests\Unit\Models;

use App\Models\FeedingLog;
use App\Models\FeedingLogIngredient;
use App\Models\VisitLog;
use App\Models\Livestock;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\NutrientDetail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FeedingLogTest extends TestCase
{

    use RefreshDatabase;

    public function test_feeding_log_has_expected_columms()
    {
        $this->assertTrue(Schema::hasColumn('feeding_logs','id'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','external_id'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','visit_date'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','body_weight'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','category'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','is_lactating'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','lactation_stage'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','is_pregnant'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','ave_daily_gain'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','milk_yield_per_day'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','total_cost_per_day'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_logs','feed_cost_per_kg'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','milk_price'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','income'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','livestock_id'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','created_by'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','srp_year_id'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','ration_name'), 1);
        $this->assertTrue(Schema::hasColumn('feeding_logs','total_as_fed_kg'), 1);

    }

    public function test_feeding_log_belongs_livestock(){

        $livestock =  Livestock::factory()->create();
        $feedinglog = FeedingLog::factory()->create(['livestock_id' => $livestock->id]);

        $this->assertInstanceOf(Livestock::class, $feedinglog->livestock);

    }    

    public function test_feeding_log_has_one_visitlog(){

        $feedinglog = FeedingLog::factory()->create();
        $visitlog =  VisitLog::factory()->create(['feeding_log_id' => $feedinglog->id]);

        $this->assertInstanceOf(VisitLog::class, $feedinglog->visitLog);

    }      


    public function test_user_has_many_feeding_logs_created_by(){

        $user =  User::factory()->create();
        $feedingLogA = FeedingLog::factory()->create(['created_by' => $user->id]);
        $feedingLogB =  FeedingLog::factory()->create(['created_by' => $user->id]);
    
        $this->assertTrue($user->feedingLogsCreatedBys->contains($feedingLogA));

        $this->assertEquals(2, $user->feedingLogsCreatedBys->count());

    }   

    /**
     * Tests for pivot feeding log ingredients
     */
    public function test_feeding_log_ingredients_has_expected_columms()
    {
        $this->assertTrue(Schema::hasColumn('feeding_log_ingredients','id'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_log_ingredients','feeding_log_id'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_log_ingredients','ingredient_id'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_log_ingredients','quantity'), 1); 
        $this->assertTrue(Schema::hasColumn('feeding_log_ingredients','price_at_date'), 1); 

    }

    
    public function test_feeding_belongs_to_many_ingredients(){

        $ingredient = Ingredient::factory()->create();
        $feedingLog = FeedingLog::factory()->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $feedingLog->ingredients);

    }

    public function test_feeding_has_many_feeding_log_ingredients(){

        $ingredient = Ingredient::factory()->create();
        $feedingLog = FeedingLog::factory()->create();
        $feedingLogIngredient = new FeedingLogIngredient;
        $feedingLogIngredient->feeding_log_id = $feedingLog->id;
        $feedingLogIngredient->ingredient_id = $ingredient->id;
        $feedingLogIngredient->save();

        //Ingredient existins in the category's ingredient collection
        $this->assertTrue($feedingLog->feedingLogIngredients->contains($feedingLogIngredient));

        //Many ingredients under category
        $this->assertEquals(1, $feedingLog->feedingLogIngredients->count());

    }

    public function test_feeding_has_many_nutrient_details(){

        $feedingLog = FeedingLog::factory()->create();
        $nutrientDetail1 = NutrientDetail::factory()->create(['feeding_log_id' => $feedingLog->id]);
        $nutrientDetail2 = NutrientDetail::factory()->create(['feeding_log_id' => $feedingLog->id]);

        $this->assertTrue($feedingLog->nutrientDetails->contains($nutrientDetail1));

        $this->assertEquals(2, $feedingLog->nutrientDetails->count());

    }


    /**
     * Tests Nutrient Details table
     */
    public function test_nutrient_details_has_expected_columms()
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
        $this->assertTrue(Schema::hasColumn('nutrient_details','feeding_log_id'), 1); 
        $this->assertTrue(Schema::hasColumn('nutrient_details','created_at'), 1);
        $this->assertTrue(Schema::hasColumn('nutrient_details','updated_at'), 1);

    }

}
