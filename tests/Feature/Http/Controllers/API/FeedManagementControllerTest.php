<?php

namespace Tests\Features\Http\Controllers\Api;

use App\Models\SrpYear;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientSrpYear;
use App\Models\User;
use App\Models\FeedingLog;
use App\Models\FeedingLogIngredient;
use App\Models\NutrientDetail;
use App\Models\UserRoles;
use App\Models\UserAdminLevelTwo;


use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;


class FeedManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSrpYearSingle()
    {
       
        $srpYear1 = SrpYear::factory()->create();

        //check if api endpoint is existing
        $response = $this->json('get', 'api/srpyears');
        $response->assertStatus(Response::HTTP_OK);
        

        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->where('year', $srpYear1->year)
                         ->etc()
                 )
        );
        

    }


    public function testSrpYearMultiple()
    {
       
        $srpYear1 = SrpYear::factory()->create();
        $srpYear2 = SrpYear::factory()->create();
        $srpYear3 = SrpYear::factory()->create();

        $response = $this->json('get', 'api/srpyears');
        $response->assertStatus(Response::HTTP_OK);
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(3)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->where('year', $srpYear1->year)
                         ->etc()
                 )
        );
    }

    
    public function testSrpYearEmpty()
    {
       
        $response = $this->json('get', 'api/srpyears');
        $response->assertStatus(Response::HTTP_OK);
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(0)
        );
    
    }

    
    public function testIngredientsWithPrice_SingleCateogry_SingleIngredient()
    {
       
        $ingredient1 = Ingredient::factory()->create();
        $srpYear1 = SrpYear::factory()->create();

        $IngredientSrpYear1 = $this->createIngredientSrpYear($ingredient1->id, $srpYear1->id, 505.50);
       
        $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear1->id);
        $response->assertStatus(Response::HTTP_OK);

        //check parent category first
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->where('name', $ingredient1->category->name)     
                         ->etc()
                 )
        );

        //check if ingredient values are correct w/ correct column names
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->where('name', $ingredient1->category->name)
                         ->where('display_order', $ingredient1->category->display_order)
                         ->where('ingredients.0.name', $ingredient1->name)        
                         ->where('ingredients.0.dm',  strval($ingredient1->dm))    
                         ->where('ingredients.0.me',  strval($ingredient1->me))    
                         ->where('ingredients.0.cp',  strval($ingredient1->cp))    
                         ->where('ingredients.0.ndf', strval($ingredient1->ndf))    
                         ->where('ingredients.0.tdn', strval($ingredient1->tdn))    
                         ->where('ingredients.0.min', strval($ingredient1->min))  
                         ->where('ingredients.0.max', strval($ingredient1->max))  
                         ->where('ingredients.0.preload', strval($ingredient1->preload))       
                         ->where('ingredients.0.category_id', strval($ingredient1->category_id))   
                         ->where('ingredients.0.price', strval($IngredientSrpYear1->price))   
                         ->etc()
                 )
        );
      
    }
   


    public function testIngredientsWithPrice_SingleCategory_MultipleIngredients()
    {
       
        //Create 3 ingredients under same category
        $category = Category::factory()
                    ->has(Ingredient::factory()->count(3))
                    ->create();

        $srpYear1 = SrpYear::factory()->create();

        $ingredientsList = Ingredient::all(); 
        
        $IngredientSrpYear1 = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear1->id, 1111.11);
        $IngredientSrpYear2 = $this->createIngredientSrpYear($ingredientsList->get(1)->id, $srpYear1->id, 2222.22);
        $IngredientSrpYear3 = $this->createIngredientSrpYear($ingredientsList->get(2)->id, $srpYear1->id, 3333.33);


        $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear1->id);
        $response->assertStatus(Response::HTTP_OK);

        //check parent category first
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)                           
                         ->where('name', $category->name)     
                         ->etc()
                 )
        );

        //check if ingredient values are correct w/ correct column names
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->where('name', $category->name)
                         ->where('display_order', strval($category->display_order))
                         //1st ingredient
                         ->where('ingredients.0.name', $ingredientsList->get(0)->name)        
                         ->where('ingredients.0.dm',  strval($ingredientsList->get(0)->dm))    
                         ->where('ingredients.0.me',  strval($ingredientsList->get(0)->me))    
                         ->where('ingredients.0.cp',  strval($ingredientsList->get(0)->cp))    
                         ->where('ingredients.0.ndf', strval($ingredientsList->get(0)->ndf))    
                         ->where('ingredients.0.tdn', strval($ingredientsList->get(0)->tdn))    
                         ->where('ingredients.0.min', strval($ingredientsList->get(0)->min))  
                         ->where('ingredients.0.max', strval($ingredientsList->get(0)->max))  
                         ->where('ingredients.0.preload', strval($ingredientsList->get(0)->preload))  
                         ->where('ingredients.0.category_id', strval($ingredientsList->get(0)->category_id))     
                         ->where('ingredients.0.price', strval($IngredientSrpYear1->price))    
                         //2nd ingredient
                         ->where('ingredients.1.name', $ingredientsList->get(1)->name)        
                         ->where('ingredients.1.dm',  strval($ingredientsList->get(1)->dm))    
                         ->where('ingredients.1.me',  strval($ingredientsList->get(1)->me))    
                         ->where('ingredients.1.cp',  strval($ingredientsList->get(1)->cp))    
                         ->where('ingredients.1.ndf', strval($ingredientsList->get(1)->ndf))    
                         ->where('ingredients.1.tdn', strval($ingredientsList->get(1)->tdn))    
                         ->where('ingredients.1.min', strval($ingredientsList->get(1)->min))  
                         ->where('ingredients.1.max', strval($ingredientsList->get(1)->max)) 
                         ->where('ingredients.1.preload', strval($ingredientsList->get(1)->preload))      
                         ->where('ingredients.1.category_id', strval($ingredientsList->get(1)->category_id))     
                         ->where('ingredients.1.price', strval($IngredientSrpYear2->price))    
                          //3rd ingredient
                         ->where('ingredients.2.name', $ingredientsList->get(2)->name)        
                         ->where('ingredients.2.dm',  strval($ingredientsList->get(2)->dm))    
                         ->where('ingredients.2.me',  strval($ingredientsList->get(2)->me))    
                         ->where('ingredients.2.cp',  strval($ingredientsList->get(2)->cp))    
                         ->where('ingredients.2.ndf', strval($ingredientsList->get(2)->ndf))    
                         ->where('ingredients.2.tdn', strval($ingredientsList->get(2)->tdn))    
                         ->where('ingredients.2.min', strval($ingredientsList->get(2)->min))  
                         ->where('ingredients.2.max', strval($ingredientsList->get(2)->max))  
                         ->where('ingredients.2.preload', strval($ingredientsList->get(2)->preload))      
                         ->where('ingredients.2.category_id', strval($ingredientsList->get(2)->category_id))     
                         ->where('ingredients.2.price', strval($IngredientSrpYear3->price))    
                         ->etc()
                 )
        );

        
      
    }

    public function testIngredientsWithPrice_MultipleCategories_MultipleIngredients(){

            //Create 4 ingredients under different categories, 2 each
            $category1 = Category::factory()
                        ->has(Ingredient::factory()->count(2))
                        ->create();

            //Create 2 ingredients under different categories
            $category2 = Category::factory()
                        ->has(Ingredient::factory()->count(2))
                        ->create();            

            $srpYear1 = SrpYear::factory()->create();

            $ingredientsList = Ingredient::all(); 

            $IngredientSrpYear1 = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear1->id, 101.11);
            $IngredientSrpYear2 = $this->createIngredientSrpYear($ingredientsList->get(1)->id, $srpYear1->id, 202.22);
            $IngredientSrpYear3 = $this->createIngredientSrpYear($ingredientsList->get(2)->id, $srpYear1->id, 303.33);
            $IngredientSrpYear4 = $this->createIngredientSrpYear($ingredientsList->get(3)->id, $srpYear1->id, 404.44);

            $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear1->id);
            $response->assertStatus(Response::HTTP_OK);

        

            //check parent category first
            $response->assertJson(fn (AssertableJson $json) =>
                       $json->has(2)
                            //first category element
                            ->has('0', fn ($json) =>                    
                                $json->where('id', 1)
                                        ->where('name', $category1->name)
                                        ->where('system_name', $category1->system_name)
                                        ->etc()
                                )
                            //2nd category element
                            ->has('1', fn ($json) =>                    
                                $json->where('id', 2)
                                        ->where('name', $category2->name)
                                        ->where('system_name', $category2->system_name)
                                        ->etc()
                                )
                                
            );  


            //check ingredients under correct category (there should be 2 each)
            $response->assertJson(fn (AssertableJson $json) =>
                       $json->has(2)
                            //first category element
                            ->has('0', fn ($json) =>                    
                                $json->where('id', 1)
                                        ->where('name', $category1->name)
                                        //1st ingredient
                                        ->where('ingredients.0.name', $ingredientsList->get(0)->name)        
                                        ->where('ingredients.0.dm',  strval($ingredientsList->get(0)->dm))    
                                        ->where('ingredients.0.me',  strval($ingredientsList->get(0)->me))    
                                        ->where('ingredients.0.cp',  strval($ingredientsList->get(0)->cp))    
                                        ->where('ingredients.0.ndf', strval($ingredientsList->get(0)->ndf))    
                                        ->where('ingredients.0.tdn', strval($ingredientsList->get(0)->tdn))    
                                        ->where('ingredients.0.min', strval($ingredientsList->get(0)->min))  
                                        ->where('ingredients.0.max', strval($ingredientsList->get(0)->max))      
                                        ->where('ingredients.0.category_id', strval($ingredientsList->get(0)->category_id))     
                                        ->where('ingredients.0.price', strval($IngredientSrpYear1->price)) 
                                        //2nd ingredient
                                        ->where('ingredients.1.name', $ingredientsList->get(1)->name)        
                                        ->where('ingredients.1.dm',  strval($ingredientsList->get(1)->dm))    
                                        ->where('ingredients.1.me',  strval($ingredientsList->get(1)->me))    
                                        ->where('ingredients.1.cp',  strval($ingredientsList->get(1)->cp))    
                                        ->where('ingredients.1.ndf', strval($ingredientsList->get(1)->ndf))    
                                        ->where('ingredients.1.tdn', strval($ingredientsList->get(1)->tdn))    
                                        ->where('ingredients.1.min', strval($ingredientsList->get(1)->min))  
                                        ->where('ingredients.1.max', strval($ingredientsList->get(1)->max))      
                                        ->where('ingredients.1.category_id', strval($ingredientsList->get(1)->category_id))     
                                        ->where('ingredients.1.price', strval($IngredientSrpYear2->price))    
                                        //only 2 ingredients should be in this category
                                        ->missing('ingredients.2.name')
                                        ->missing('ingredients.3.name')
                                        ->etc()
                                        
                                )
                            //2nd category element
                            ->has('1', fn ($json) =>                    
                                $json->where('id', 2)
                                        ->where('name', $category2->name)
                                        //3rd ingredient
                                        ->where('ingredients.0.name', $ingredientsList->get(2)->name)        
                                        ->where('ingredients.0.dm',  strval($ingredientsList->get(2)->dm))    
                                        ->where('ingredients.0.me',  strval($ingredientsList->get(2)->me))    
                                        ->where('ingredients.0.cp',  strval($ingredientsList->get(2)->cp))    
                                        ->where('ingredients.0.ndf', strval($ingredientsList->get(2)->ndf))    
                                        ->where('ingredients.0.tdn', strval($ingredientsList->get(2)->tdn))    
                                        ->where('ingredients.0.min', strval($ingredientsList->get(2)->min))  
                                        ->where('ingredients.0.max', strval($ingredientsList->get(2)->max))      
                                        ->where('ingredients.0.category_id', strval($ingredientsList->get(2)->category_id))     
                                        ->where('ingredients.0.price', strval($IngredientSrpYear3->price)) 
                                        //4th ingredient
                                        ->where('ingredients.1.name', $ingredientsList->get(3)->name)        
                                        ->where('ingredients.1.dm',  strval($ingredientsList->get(3)->dm))    
                                        ->where('ingredients.1.me',  strval($ingredientsList->get(3)->me))    
                                        ->where('ingredients.1.cp',  strval($ingredientsList->get(3)->cp))    
                                        ->where('ingredients.1.ndf', strval($ingredientsList->get(3)->ndf))    
                                        ->where('ingredients.1.tdn', strval($ingredientsList->get(3)->tdn))    
                                        ->where('ingredients.1.min', strval($ingredientsList->get(3)->min))  
                                        ->where('ingredients.1.max', strval($ingredientsList->get(3)->max))      
                                        ->where('ingredients.1.category_id', strval($ingredientsList->get(3)->category_id))     
                                        ->where('ingredients.1.price', strval($IngredientSrpYear4->price))    
                                        //only 2 ingredients should be in this category
                                        ->missing('ingredients.2.name')
                                        ->missing('ingredients.3.name')
                                        ->etc()
                                )
                                
            );  

    }
    

    public function testIngredientsWithPriceBySrpYear(){

        //Create 2 ingredients under 1 category
        $category1 = Category::factory()
                    ->has(Ingredient::factory()->count(2))
                    ->create();       

        $srpYear1 = SrpYear::factory()->create();
        $srpYear2 = SrpYear::factory()->create();

        $ingredientsList = Ingredient::all(); 

        //SRP Year 1 Pricing
        $IngredientSrpYear1A = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear1->id, 10);
        $IngredientSrpYear1B = $this->createIngredientSrpYear($ingredientsList->get(1)->id, $srpYear1->id, 20);
        
        //SRP Year 2 Pricing
        $IngredientSrpYear2A = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear2->id, 777.77);
        $IngredientSrpYear2B = $this->createIngredientSrpYear($ingredientsList->get(1)->id, $srpYear2->id, 888.88);

        //Call Ingredients for srp year 1
        $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear1->id);
        $response->assertStatus(Response::HTTP_OK);

        //check if returned price is correct based on SRP Year (1st srpyear)
        $response->assertJson(fn (AssertableJson $json) =>
                   $json->has(1)
                        //first category element
                        ->has('0', fn ($json) =>                    
                            $json->where('id', 1)
                                    ->where('name', $category1->name)
                                    //1st ingredient
                                    ->where('ingredients.0.name', $ingredientsList->get(0)->name)        
                                    ->where('ingredients.0.dm',  strval($ingredientsList->get(0)->dm))    
                                    ->where('ingredients.0.me',  strval($ingredientsList->get(0)->me))    
                                    ->where('ingredients.0.cp',  strval($ingredientsList->get(0)->cp))    
                                    ->where('ingredients.0.ndf', strval($ingredientsList->get(0)->ndf))    
                                    ->where('ingredients.0.tdn', strval($ingredientsList->get(0)->tdn))    
                                    ->where('ingredients.0.min', strval($ingredientsList->get(0)->min))  
                                    ->where('ingredients.0.max', strval($ingredientsList->get(0)->max))      
                                    ->where('ingredients.0.category_id', strval($ingredientsList->get(0)->category_id))     
                                    ->where('ingredients.0.price', strval($IngredientSrpYear1A->price)) 
                                    //2nd ingredient
                                    ->where('ingredients.1.name', $ingredientsList->get(1)->name)        
                                    ->where('ingredients.1.dm',  strval($ingredientsList->get(1)->dm))    
                                    ->where('ingredients.1.me',  strval($ingredientsList->get(1)->me))    
                                    ->where('ingredients.1.cp',  strval($ingredientsList->get(1)->cp))    
                                    ->where('ingredients.1.ndf', strval($ingredientsList->get(1)->ndf))    
                                    ->where('ingredients.1.tdn', strval($ingredientsList->get(1)->tdn))    
                                    ->where('ingredients.1.min', strval($ingredientsList->get(1)->min))  
                                    ->where('ingredients.1.max', strval($ingredientsList->get(1)->max))      
                                    ->where('ingredients.1.category_id', strval($ingredientsList->get(1)->category_id))     
                                    ->where('ingredients.1.price', strval($IngredientSrpYear1B->price))    
                                    //only 2 ingredients should be in this category
                                    ->missing('ingredients.2.name')
                                    ->missing('ingredients.3.name')
                                    ->etc()
                                    
                            )                            
        );
        

         //Call Ingredients for srp year 2
         $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear2->id);
         $response->assertStatus(Response::HTTP_OK);
 
         //check if returned price is correct based on SRP Year (2nd srpyear)
         $response->assertJson(fn (AssertableJson $json) =>
                    $json->has(1)
                         //first category element
                         ->has('0', fn ($json) =>                    
                             $json->where('id', 1)
                                     ->where('name', $category1->name)
                                     //1st ingredient
                                     ->where('ingredients.0.name', $ingredientsList->get(0)->name)        
                                     ->where('ingredients.0.dm',  strval($ingredientsList->get(0)->dm))    
                                     ->where('ingredients.0.me',  strval($ingredientsList->get(0)->me))    
                                     ->where('ingredients.0.cp',  strval($ingredientsList->get(0)->cp))    
                                     ->where('ingredients.0.ndf', strval($ingredientsList->get(0)->ndf))    
                                     ->where('ingredients.0.tdn', strval($ingredientsList->get(0)->tdn))    
                                     ->where('ingredients.0.min', strval($ingredientsList->get(0)->min))  
                                     ->where('ingredients.0.max', strval($ingredientsList->get(0)->max))      
                                     ->where('ingredients.0.category_id', strval($ingredientsList->get(0)->category_id))     
                                     ->where('ingredients.0.price', strval($IngredientSrpYear2A->price)) 
                                     //2nd ingredient
                                     ->where('ingredients.1.name', $ingredientsList->get(1)->name)        
                                     ->where('ingredients.1.dm',  strval($ingredientsList->get(1)->dm))    
                                     ->where('ingredients.1.me',  strval($ingredientsList->get(1)->me))    
                                     ->where('ingredients.1.cp',  strval($ingredientsList->get(1)->cp))    
                                     ->where('ingredients.1.ndf', strval($ingredientsList->get(1)->ndf))    
                                     ->where('ingredients.1.tdn', strval($ingredientsList->get(1)->tdn))    
                                     ->where('ingredients.1.min', strval($ingredientsList->get(1)->min))  
                                     ->where('ingredients.1.max', strval($ingredientsList->get(1)->max))      
                                     ->where('ingredients.1.category_id', strval($ingredientsList->get(1)->category_id))     
                                     ->where('ingredients.1.price', strval($IngredientSrpYear2B->price))    
                                     //only 2 ingredients should be in this category
                                     ->missing('ingredients.2.name')
                                     ->missing('ingredients.3.name')
                                     ->etc()
                                     
                             )                            
         );       
    }




    //Test case for data type returned
    public function test_IngredientsBySrp_ReturnedDataTypes()
    {
       
        $ingredient1 = Ingredient::factory()->create();
        $srpYear1 = SrpYear::factory()->create();

        $IngredientSrpYear1 = $this->createIngredientSrpYear($ingredient1->id, $srpYear1->id, 520.5);
       
        $response = $this->json('get', 'api/ingredientsbysrp/'.$srpYear1->id);
        $response->assertStatus(Response::HTTP_OK);

        //check for correctness of data type
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'ingredients' => 'array',
                        'ingredients.0.name' => 'string',
                        /*
                            //Note: This can be improved, currently the returned properties are seen as string. 
                            //      Maybe because of how join was made

                            'ingredients.0.dm' => ['double', 'integer'],
                            'ingredients.0.me' => ['double', 'integer'],
                            'ingredients.0.cp' => ['double', 'integer'],
                            'ingredients.0.ndf' => ['double', 'integer'],
                            'ingredients.0.tdn' => ['double', 'integer'],
                            'ingredients.0.min' => ['double', 'integer'],
                            'ingredients.0.max' => ['double', 'integer'],
                            'ingredients.0.price' => ['double', 'integer'],
                        */
                    ])
                    ->etc()
                 )
        );
      
    }

    
      
    public function test_SrpYearSingle_ReturnDataTypes()
    {
       
        $srpYear1 = SrpYear::factory()->create();

        //check if api endpoint is existing
        $response = $this->json('get', 'api/srpyears');
        $response->assertStatus(Response::HTTP_OK);
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has(1)
                 ->first(fn ($json) =>
                    $json->where('id', 1)
                         ->whereAllType([
                            'id' => 'integer',
                            'year' => 'string',
                        ])
                        ->etc()
                 )
        );

    }


    public function test_feeding_log_sync_down_structure(){

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
       
        $feedingLog = FeedingLog::factory()
                      ->has(NutrientDetail::factory()->count(2))
                      ->has(FeedingLogIngredient::factory()->count(2))
                      ->create();

        /* Setup Visibility*/
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $feedingLog->livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save(); 

        $response = $this->actingAs($user)->json('get', 'api/getFeedingLogsAssigned', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => date('Y-m-d')
        ]);

        
        $response->assertStatus(200);

        // check structure for returned feedingLogs
        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->where('feedingLogs.0.id', $feedingLog->id)
                ->where('feedingLogs.0.external_id', $feedingLog->external_id)
                ->where('feedingLogs.0.visit_date', $feedingLog->visit_date)
                ->where('feedingLogs.0.body_weight', strval($feedingLog->body_weight))
                ->where('feedingLogs.0.category', $feedingLog->category)
                ->where('feedingLogs.0.lactation_stage', $feedingLog->lactation_stage)
                ->has('feedingLogs.0.is_lactating')
                ->has('feedingLogs.0.is_pregnant')
                ->where('feedingLogs.0.ave_daily_gain', strval($feedingLog->ave_daily_gain))
                ->where('feedingLogs.0.milk_yield_per_day', strval($feedingLog->milk_yield_per_day))
                ->where('feedingLogs.0.milk_price', strval($feedingLog->milk_price))
                ->where('feedingLogs.0.total_cost_per_day', strval($feedingLog->total_cost_per_day))
                ->where('feedingLogs.0.feed_cost_per_kg', strval($feedingLog->feed_cost_per_kg))
                ->where('feedingLogs.0.income', strval($feedingLog->income))
                ->where('feedingLogs.0.livestock_id', strval($feedingLog->livestock_id))
                ->where('feedingLogs.0.created_by', strval($feedingLog->created_by))
                ->has('feedingLogs.0.created_at')
                ->has('feedingLogs.0.updated_at')
                ->where('feedingLogs.0.pregnancy_month', $feedingLog->pregnancy_month)
                ->where('feedingLogs.0.fat_protein', strval($feedingLog->fat_protein))
                ->where('feedingLogs.0.srp_year_id', strval($feedingLog->srp_year_id))
                ->where('feedingLogs.0.ration_name', $feedingLog->ration_name)
                ->etc()
            );

        //check structure for additional related info - feeding log ingredients
        $response->assertJson(fn (AssertableJson $json) =>
            $json
            ->has('feedingLogs.0.feeding_log_ingredients')
            ->has('feedingLogs.0.feeding_log_ingredients.0.id')
            ->has('feedingLogs.0.feeding_log_ingredients.0.feeding_log_id')
            ->has('feedingLogs.0.feeding_log_ingredients.0.ingredient_id')
            ->has('feedingLogs.0.feeding_log_ingredients.0.quantity')
            ->has('feedingLogs.0.feeding_log_ingredients.0.price_at_date')
            ->has('feedingLogs.0.feeding_log_ingredients.1')        //check that there is a second ingredient (based on create test scenario)
            ->etc()
        );        


        //check structure for additional related info - nutrient details
                $response->assertJson(fn (AssertableJson $json) =>
                $json
                ->has('feedingLogs.0.nutrient_details')
                ->has('feedingLogs.0.nutrient_details.0.id')
                ->has('feedingLogs.0.nutrient_details.0.type')
                ->has('feedingLogs.0.nutrient_details.0.dm')
                ->has('feedingLogs.0.nutrient_details.0.me')
                ->has('feedingLogs.0.nutrient_details.0.cp')
                ->has('feedingLogs.0.nutrient_details.0.ndf')
                ->has('feedingLogs.0.nutrient_details.0.tdn')
                ->has('feedingLogs.0.nutrient_details.0.ca')
                ->has('feedingLogs.0.nutrient_details.0.p')
                ->has('feedingLogs.0.nutrient_details.0.feeding_log_id')
                ->etc()
            );    
    }

    public function test_feeding_log_sync_down_no_visibility_date(){

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
       
        $feedingLog = FeedingLog::factory()
                      ->has(NutrientDetail::factory()->count(2))
                      ->has(FeedingLogIngredient::factory()->count(2))
                      ->create();

        /* Setup Visibility*/
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $feedingLog->livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save(); 

        $response = $this->actingAs($user)->json('get', 'api/getFeedingLogsAssigned', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => "2021-01-16"        //no feeding log updated on these dates
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
        $json
            ->missing('feedingLogs.0.id')
            ->etc()
        );

    }

    public function test_feeding_log_sync_down_no_visibility_userassgination(){

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
       
        $feedingLog = FeedingLog::factory()
                      ->has(NutrientDetail::factory()->count(2))
                      ->has(FeedingLogIngredient::factory()->count(2))
                      ->create();

        /* Do NOT Setup Visibility*/
            //$userAssignation = new UserAdminLevelTwo;
            //$userAssignation->user_id = $user->id;
            //$userAssignation->admin_level_two_id = $feedingLog->livestock->farmer->adminLevelThree->adminLevelTwo->id;
            //$userAssignation->save(); 

        $response = $this->actingAs($user)->json('get', 'api/getFeedingLogsAssigned', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => date('Y-m-d')
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
        $json
            ->missing('feedingLogs.0.id')
            ->etc()
        );

    }

    public function test_feeding_log_sync_down_not_authenticated(){

        $response = $this->json('get', 'api/getFeedingLogsAssigned', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => date('Y-m-d')
        ]);

        $response->assertStatus(401);

    }   


    public function test_feeding_log_sync_up_not_authenticated(){

        $response = $this->json('post', 'api/syncUpFeedingLogs', [
            "syncFromDate" => "2021-01-15",
            "syncToDate" => date('Y-m-d')
        ]);

        $response->assertStatus(401);

    }   

    public function test_feeding_log_sync_up_multiple(){

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $feedingLogs = FeedingLog::factory()->count(2)
            ->has(NutrientDetail::factory()->count(2))
            ->has(FeedingLogIngredient::factory()->count(2))
            ->create();

        $feedingLogs[0]->ration_name = 'Update Ration';

        $response = $this->actingAs($user)->json('post', 'api/syncUpFeedingLogs', [
            "feedingLogs" => $feedingLogs,
        ]);

    

        $updatedRecord = FeedingLog::find($feedingLogs[0]->id);
        
        $response->assertStatus(200);
        $this->assertEquals($updatedRecord->ration_name, 'Update Ration');
 
        $response->assertJson(fn (AssertableJson $json) =>
        $json
            ->where('message', 'Synched Up successfully 2 record(s)')
        );     


    }



    /* Helper Method to create IngredientsSRP and establish relationship*/
    protected function createIngredientSrpYear($ingredientId, $srpYearId, $price){


        $ingredientSrpYear = new IngredientSrpYear;
        $ingredientSrpYear->ingredient_id = $ingredientId;
        $ingredientSrpYear->srp_year_id = $srpYearId;
        $ingredientSrpYear->price = $price;
        $ingredientSrpYear->save();    

        return $ingredientSrpYear;


    }






}
