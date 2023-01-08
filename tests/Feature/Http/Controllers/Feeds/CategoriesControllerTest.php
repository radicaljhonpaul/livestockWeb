<?php

namespace Tests\Feature\Http\Controllers\Feeds;

use App\Models\User;
use App\Models\Category;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;
    
    
    public function test_page_rendered_for_admin()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');
        
        $response->assertStatus(200);

        ob_get_clean();

    
    }


    public function test_categories_view_all_columns()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $category1 = Category::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');
        
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Feeds/CategoriesList')
            ->has('Categories', 1)               
            //expected columns for logic / display 
            ->where('Categories.0.id', $category1->id)
            ->where('Categories.0.name', $category1->name)
        );

        ob_get_clean();

    }

    public function test_categories_view_multiple()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        //Create 3 categories
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $category3 = Category::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');
        
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Feeds/CategoriesList')
            ->has('Categories', 3)       
        );

        ob_get_clean();

    }
 
    public function test_add_category_allowed_as_admin(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertEquals('Category XYZ', $category->name);
    
        ob_get_clean();

    }


    public function test_add_category_validation_required(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);


        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

        $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 'name' => '',
                                    ]        
                                    );

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['name' => 'The name field is required.']);
    
    
        ob_get_clean();

    }

    public function test_add_category_allowed_as_IFodderContentManager(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    
        //Check if record is inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertEquals('Category XYZ', $category->name);
    
        ob_get_clean();

    }

    public function test_add_category_blocked_as_Vet(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(404);
    
        //Check if record is not inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertNull($category);
    
        ob_get_clean();

    }

    public function test_add_category_blocked_as_VetAide(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(404);
    
        //Check if record is not inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertNull($category);
    
        ob_get_clean();

    }

    public function test_add_category_blocked_as_IHealthContentManager(){


        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(404);
    
        //Check if record is not inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertNull($category);
    
        ob_get_clean();

    }   

    public function test_add_category_blocked_as_ReportsUser(){


              //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);

       $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminCategories');

       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateCategory', 
                                    [ 
                                        'name' => 'Category XYZ',
                                        //TODO: Update test with system_name once implemented for future proofing
                                    ]        
                                    ); 

        $response->assertStatus(404);
    
        //Check if record is not inserted
        $category = Category::where('id', '1')->get()->first();
        $this->assertNull($category);
    
        ob_get_clean();
       
    }   
   


}
