<?php

namespace Tests\Feature\Http\Controllers\Feeds;

use App\Models\User;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class IngredientsControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public string $list_route_name = "/AdminIngredients";
  
    public function test_page_rendered_for_admin()
    {
   
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].$this->list_route_name);
        
        $response->assertStatus(200);

        ob_get_clean();
    
    }


    public function test_ingredients_view_all_columns()
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $ingredient1 = Ingredient::factory()->create();

        $url = '/'.$data['role'].$this->list_route_name.'?category_id='.$ingredient1->category->id;

        $response = $this->actingAs($user)->get($url);
        
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Feeds/IngredientsList')            
            //1 ingredient
            ->has('Ingredients.data', 1)  
            //expected columns for logic / display (paginated is under data wrapper)
            ->where('Ingredients.data.0.id', $ingredient1->id)
            ->where('Ingredients.data.0.name', $ingredient1->name)
            ->where('Ingredients.data.0.dm', strval($ingredient1->dm))
            ->where('Ingredients.data.0.me', strval($ingredient1->me))
            ->where('Ingredients.data.0.cp', strval($ingredient1->cp))
            ->where('Ingredients.data.0.ndf', strval($ingredient1->ndf))
            ->where('Ingredients.data.0.tdn', strval($ingredient1->tdn))
            ->where('Ingredients.data.0.ca', strval($ingredient1->ca))
            ->where('Ingredients.data.0.p', strval($ingredient1->p))
            ->where('Ingredients.data.0.min', strval($ingredient1->min))
            ->where('Ingredients.data.0.max', strval($ingredient1->max))
            ->where('Ingredients.data.0.category_id', strval($ingredient1->category_id))
            ->has('Category')  
            ->where('Category.id', $ingredient1->category->id)

        );

        ob_get_clean();
        

    }

    
    
    public function test_ingredients_view_multiple()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    

        //Create 4 ingredients under different categories, 2 each
        $category1 = Category::factory()
                    ->has(Ingredient::factory()->count(2))
                    ->create();

                
        $category2 = Category::factory()
                ->has(Ingredient::factory()->count(2))
                ->create();            

        
        $url = '/'.$data['role'].$this->list_route_name.'?category_id='.$category1->id;
        
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
                ->component('Feeds/IngredientsList')            
                //2 ingredient should be listed, based on parent category 1
                ->has('Ingredients.data', 2)   
                ->where('Category.id', $category1->id)
                ->where('Category.name', $category1->name)
        );

        ob_get_clean();

    }
    


    public function test_add_ingredient_valid(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
            [ 'name' => 'Chemical X',
                'dm' => '1.1',
                'me' => '2.1',
                'cp' => '3.1',
                'ndf' => '4.1',
                'tdn' => '5.1',
                'ca' => '6.1',
                'p' => '7.1',
            ]        
        );

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $ingredient = Ingredient::where('id', '1')->get()->first();
        $this->assertEquals('Chemical X', $ingredient->name);
    
        ob_get_clean();

    }

   
    
    public function test_add_ingredient_validation_numbers_only(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

       $user = User::factory()->create(['id' => 1]);
       $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
                                    [ 'name' => 'Chemical X',
                                      'dm' => '1.1z',
                                      'me' => '2.1z',
                                      'cp' => '3.1z',
                                      'ndf' => '4.1z',
                                      'tdn' => '5.1z',
                                      'ca' => '6.1z',
                                      'p' => '7.1z',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrorsIn('add', ['dm' => 'The dm must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['me' => 'The me must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['cp' => 'The cp must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['ndf' => 'The ndf must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['tdn' => 'The tdn must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['ca' => 'The ca must be a number.']);
        $response->assertSessionHasErrorsIn('add', ['p' => 'The p must be a number.']);

        
        ob_get_clean();  

    }

       

    
    public function test_add_ingredient_validation_required(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
                                    [ 

                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['name' => 'The name field is required.']);
        $response->assertSessionHasErrorsIn('add', ['dm' => 'The dm field is required.']);
        $response->assertSessionHasErrorsIn('add', ['me' => 'The me field is required.']);
        $response->assertSessionHasErrorsIn('add', ['cp' => 'The cp field is required.']);
        $response->assertSessionHasErrorsIn('add', ['ndf' => 'The ndf field is required.']);
        $response->assertSessionHasErrorsIn('add', ['tdn' => 'The tdn field is required.']);
        $response->assertSessionHasErrorsIn('add', ['ca' => 'The ca field is required.']);
        $response->assertSessionHasErrorsIn('add', ['p' => 'The p field is required.']);

        
        ob_get_clean();  
    }



    public function test_add_ingredient_validation_numbers_up_to_2_decimal_only(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
                                    [ 'name' => 'Chemical X',
                                      'dm' => '1.1111',
                                      'me' => '2.1111',
                                      'cp' => '3.1111',
                                      'ndf' => '4.1111',
                                      'tdn' => '5.1111',
                                      'ca' => '6.11111',
                                      'p' => '7.11111',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['dm' => 'The dm format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['me' => 'The me format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['cp' => 'The cp format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['ndf' => 'The ndf format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['tdn' => 'The tdn format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['ca' => 'The ca format is invalid.']);
        $response->assertSessionHasErrorsIn('add', ['p' => 'The p format is invalid.']);

        ob_get_clean();  

    }   

      

    public function test_add_ingredient_numbers_validation_max(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
                                    [ 'name' => 'Chemical X teeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeest',
                                      'dm' => '100.10',
                                      'me' => '101',
                                      'cp' => '101',
                                      'ndf' => '101',
                                      'tdn' => '101',
                                      'ca' => '101',
                                      'p' => '101',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['name' => 'The name must not be greater than 50 characters.']);
        $response->assertSessionHasErrorsIn('add', ['dm' => 'The dm must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['me' => 'The me must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['cp' => 'The cp must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['ndf' => 'The ndf must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['tdn' => 'The tdn must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['ca' => 'The ca must not be greater than 100.']);
        $response->assertSessionHasErrorsIn('add', ['p' => 'The p must not be greater than 100.']);

        ob_get_clean();  

    }   

     

    public function test_edit_ingredient_valid(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();
       $ingredient = Ingredient::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedIngredient?category_id=1', 
                                    [ 
                                      'id' => '1',
                                      'name' => 'Chemical X',
                                      'dm' => '1.1',
                                      'me' => '2.1',
                                      'cp' => '3.1',
                                      'ndf' => '4.1',
                                      'tdn' => '5.1',
                                      'ca' => '6.1',
                                      'p' => '7.1',
                                      'min' => '8.1',
                                      'max' => '9.1',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $ingredient = Ingredient::where('id', '1')->get()->first();
        $this->assertEquals('Chemical X', $ingredient->name);
        $this->assertEquals('1.1', $ingredient->dm);
        $this->assertEquals('2.1', $ingredient->me);
        $this->assertEquals('3.1', $ingredient->cp);
        $this->assertEquals('4.1', $ingredient->ndf);
        $this->assertEquals('5.1', $ingredient->tdn);
        $this->assertEquals('6.1', $ingredient->ca);
        $this->assertEquals('7.1', $ingredient->p);
        $this->assertEquals('8.1', $ingredient->min);
        $this->assertEquals('9.1', $ingredient->max);
    
        ob_get_clean();

    }   


    public function test_edit_ingredient_validation_required(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    

       $category = Category::factory()->create();
       $ingredient = Ingredient::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedIngredient?category_id=1', 
                                    [ 
                                        'id' => '1',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('edit', ['name' => 'The name field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['dm' => 'The dm field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['me' => 'The me field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['cp' => 'The cp field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['ndf' => 'The ndf field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['tdn' => 'The tdn field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['ca' => 'The ca field is required.']);
        $response->assertSessionHasErrorsIn('edit', ['p' => 'The p field is required.']);

        
        ob_get_clean();  
    }

    public function test_edit_ingredient_validation_numbers_only(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();
       $ingredient = Ingredient::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedIngredient?category_id=1', 
                                    [ 
                                        'id' => '1',
                                        'dm' => '1.1z',
                                        'me' => '2.1z',
                                        'cp' => '3.1z',
                                        'ndf' => '4.1z',
                                        'tdn' => '5.1z',
                                        'ca' => '6.1z',
                                        'p' => '7.1z',
                                        'min' => '8.1z',
                                        'max' => '9.1z',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('edit', ['dm' => 'The dm must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['me' => 'The me must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['cp' => 'The cp must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['ndf' => 'The ndf must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['tdn' => 'The tdn must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['ca' => 'The ca must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['p' => 'The p must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['min' => 'The min must be a number.']);
        $response->assertSessionHasErrorsIn('edit', ['max' => 'The max must be a number.']);

        
        ob_get_clean();  
    }   


    /** Additional tests in relation to User Roles */

    public function test_add_ingredient_valid_as_IFodderContentManager(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
            [ 'name' => 'Chemical X',
                'dm' => '1.1',
                'me' => '2.1',
                'cp' => '3.1',
                'ndf' => '4.1',
                'tdn' => '5.1',
                'ca' => '6.1',
                'p' => '7.1',
            ]        
        );

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $ingredient = Ingredient::where('id', '1')->get()->first();
        $this->assertEquals('Chemical X', $ingredient->name);
    
        ob_get_clean();

    }


    public function test_edit_ingredient_valid_as_IFodderContentManager(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
    
       $category = Category::factory()->create();
       $ingredient = Ingredient::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedIngredient?category_id=1', 
                                    [ 
                                      'id' => '1',
                                      'name' => 'Chemical X',
                                      'dm' => '1.1',
                                      'me' => '2.1',
                                      'cp' => '3.1',
                                      'ndf' => '4.1',
                                      'tdn' => '5.1',
                                      'ca' => '6.1',
                                      'p' => '7.1',
                                      'min' => '8.1',
                                      'max' => '9.1',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $ingredient = Ingredient::where('id', '1')->get()->first();
        $this->assertEquals('Chemical X', $ingredient->name);
        $this->assertEquals('1.1', $ingredient->dm);
        $this->assertEquals('2.1', $ingredient->me);
        $this->assertEquals('3.1', $ingredient->cp);
        $this->assertEquals('4.1', $ingredient->ndf);
        $this->assertEquals('5.1', $ingredient->tdn);
        $this->assertEquals('6.1', $ingredient->ca);
        $this->assertEquals('7.1', $ingredient->p);
        $this->assertEquals('8.1', $ingredient->min);
        $this->assertEquals('9.1', $ingredient->max);
    
        ob_get_clean();

    }   


    public function test_ingredients_view_multiple_as_IFodderContentManager()
    {
        $this->ingredients_view_multiple_allowed_role('IFodderContentManager');
    }

   public function test_ingredients_view_multiple_allowed_Vet(){

        $this->ingredients_view_multiple_allowed_role('Vet');
   }

   public function test_ingredients_view_multiple_allowed_VetAide(){

        $this->ingredients_view_multiple_allowed_role('VetAide');
   }

   public function test_ingredients_view_multiple_allowed_IHealthContentManager(){
        
        $this->ingredients_view_multiple_allowed_role('IHealthContentManager');
   }   



   /** Reusable  check for view ingredients based on Role */
   public function ingredients_view_multiple_allowed_role(String $role)
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    

        //Create 4 ingredients under different categories, 2 each
        $category1 = Category::factory()
                    ->has(Ingredient::factory()->count(2))
                    ->create();

                
        $category2 = Category::factory()
                ->has(Ingredient::factory()->count(2))
                ->create();            

        
        $url = '/'.$data['role'].$this->list_route_name.'?category_id='.$category1->id;
        
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
                ->component('Feeds/IngredientsList')            
                //2 ingredient should be listed, based on parent category 1
                ->has('Ingredients.data', 2)   
                ->where('Category.id', $category1->id)
                ->where('Category.name', $category1->name)
        );

        ob_get_clean();

    }


    /**
     * ROLES CHECK FOR ADD (BLOCKED)
     */

    public function test_add_ingredients_blocked_as_Vet()
    {
        $this->add_ingredient_blocked_as_role('Vet');
    }   

    public function test_add_ingredients_blocked_as_VetAide()
    {
        $this->add_ingredient_blocked_as_role('VetAide');
    }     
    
    public function test_add_ingredients_blocked_as_IHealthContentManager()
    {
        $this->add_ingredient_blocked_as_role('IHealthContentManager');
    }       
     
    public function test_add_ingredients_blocked_as_ReportsUser()
    {
        $this->add_ingredient_blocked_as_role('ReportsUser');
    }   

    public function add_ingredient_blocked_as_role(String $role){

            //For checking validation errors, exception handling must be removed
            //$this->withoutExceptionHandling();
    
            $user = User::factory()->create(['id' => 1]);
            $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
        
           $category = Category::factory()->create();
    
           $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
        
           $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateIngredient?category_id=1', 
                [ 'name' => 'Chemical X',
                    'dm' => '1.1',
                    'me' => '2.1',
                    'cp' => '3.1',
                    'ndf' => '4.1',
                    'tdn' => '5.1',
                    'ca' => '6.1',
                    'p' => '7.1',
                ]        
            );
    
            $response->assertStatus(404);
        
            //Check if record is NOT inserted
            $ingredient = Ingredient::where('id', '1')->get()->first();
            $this->assertNull($ingredient);
        
            ob_get_clean();
    
        }

   


    /**
     * ROLES CHECK FOR EDIT (BLOCKED)
    */

    public function test_edit_ingredient_valid_as_blocked_as_Vet(){

        $this->edit_ingredient_valid_as_blocked_as_role('Vet');

    }

    public function test_edit_ingredient_valid_as_blocked_as_VetAide(){

        $this->edit_ingredient_valid_as_blocked_as_role('VetAide');

    }

    public function test_edit_ingredient_valid_as_blocked_as_IHealthContentManager(){

        $this->edit_ingredient_valid_as_blocked_as_role('IHealthContentManager');

    }   

    public function test_edit_ingredient_valid_as_blocked_as_ReportsUser(){

        $this->edit_ingredient_valid_as_blocked_as_role('ReportsUser');

    }      

    public function edit_ingredient_valid_as_blocked_as_role(String $role){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    
       $category = Category::factory()->create();
       $ingredient = Ingredient::factory()->create();

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminIngredients?category_id=1');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedIngredient?category_id=1', 
                                    [ 
                                      'id' => '1',
                                      'name' => 'Chemical X',
                                      'dm' => '1.1',
                                      'me' => '2.1',
                                      'cp' => '3.1',
                                      'ndf' => '4.1',
                                      'tdn' => '5.1',
                                      'ca' => '6.1',
                                      'p' => '7.1',
                                      'min' => '8.1',
                                      'max' => '9.1',
                                    ]        
                                    ); 

        $response->assertStatus(404);
    
        //Check if record is NOT edited
        $ingredient = Ingredient::where('id', '1')->get()->first();
        $this->assertNotEquals('Chemical X', $ingredient->name);
    
        ob_get_clean();

    }        




}
