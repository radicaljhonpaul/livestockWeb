<?php

namespace Tests\Feature\Http\Controllers\Feeds;

use App\Models\User;
use App\Models\SrpYear;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientSrpYear;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IngredientSrpYearExport;


class SrpYearControllerTest extends TestCase
{
    use RefreshDatabase;
    
  
    public ?string $list_route_name = "/AdminPriceByYear";
    

    public function test_page_rendered_for_admin()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
        
        $response->assertStatus(200);

        ob_get_clean();

    
    }

    public function test_srp_year_view_all_columns()
    {
      
        $this->withoutExceptionHandling();
    
        $srpYear1 = SrpYear::factory()->create();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);   

        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
        
        $response->assertStatus(200);

        //dd($response->content());

        $response->assertInertia(fn ($page) => $page
            ->component('Pricing/SrpYearList')            
            //1 srpyear
            ->has('SrpYears', 1)  
            //expected columns
            ->where('SrpYears.0.id', $srpYear1->id)
            ->where('SrpYears.0.year', $srpYear1->year)
        );

        ob_get_clean();
        

    }

   

    public function test_srp_year_view_multiple()
    {
    

        $this->withoutExceptionHandling();    
        $srpYear1 = SrpYear::factory()->count(5)->create();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);   

        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
        
        $response->assertStatus(200);

        //dd($response->content());

        $response->assertInertia(fn ($page) => $page
            ->component('Pricing/SrpYearList')            
            //5 records
            ->has('SrpYears', 5)  
        );

        ob_get_clean();
        

    }

    public function test_pricebyyear_view_columns_as_Admin(){
        $this->pricebyyear_view_columns_as_role('Admin');
    }

    public function test_pricebyyear_view_columns_as_Vet(){
        $this->pricebyyear_view_columns_as_role('Vet');
    }   
    
    public function test_pricebyyear_view_columns_as_VetAide(){
        $this->pricebyyear_view_columns_as_role('VetAide');
    }  
    
    public function test_pricebyyear_view_columns_as_IHealthContentManager(){
        $this->pricebyyear_view_columns_as_role('IHealthContentManager');
    }     

    public function test_pricebyyear_view_columns_as_IFodderContentManager(){
        $this->pricebyyear_view_columns_as_role('IFodderContentManager');
    }  

    public function test_pricebyyear_view_columns_as_ReportsUser(){
        $this->pricebyyear_view_columns_as_role('ReportsUser');
    }  

    public function pricebyyear_view_columns_as_role(String $role)
    {
     
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);   
    
        //Create 2 ingredients under 1 category
        $category1 = Category::factory()
                    ->has(Ingredient::factory()->count(2))
                    ->create();   

        $srpYear1 = SrpYear::factory()->create();

        $ingredientsList = Ingredient::all(); 

        //SRP Year 1 Pricing
        $IngredientSrpYear1A = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear1->id, 10);
       
        $url = '/'.$data['role'].$this->list_route_name.'?srpyear_id='.$srpYear1->id;
        
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
                ->component('Pricing/IngredientPriceList')            
                //2 ingredientprice should be listed, based on parent srpyear_id
                ->has('IngredientSrpYears.data', 1)   
                ->where('IngredientSrpYears.data.0.srp_year_id', strval($srpYear1->id))
                ->where('IngredientSrpYears.data.0.category_name', $category1->name)
                ->where('IngredientSrpYears.data.0.name', $ingredientsList->get(0)->name)
                ->where('IngredientSrpYears.data.0.price', strval($IngredientSrpYear1A->price))
                ->where('IngredientSrpYears.data.0.id', $IngredientSrpYear1A->id)
                ->has('SrpYear')    
                ->where('SrpYear.year', $srpYear1->year)    
                
        );

        ob_get_clean();

    }


    public function test_pricebyyear_view_multiple()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);   
    
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
      
        
        $url = '/'.$data['role'].$this->list_route_name.'?srpyear_id='.$srpYear1->id;
        
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
                ->component('Pricing/IngredientPriceList')            
                //2 ingredientprice should be listed, based on parent srpyear_id
                ->has('IngredientSrpYears.data', 2)   
                ->where('IngredientSrpYears.data.0.srp_year_id', strval($srpYear1->id))
        );

        ob_get_clean();

    }

    
    public function test_download_pricelist_filename_and_type_as_Admin(){
        $this->download_pricelist_filename_and_type('Admin');
    }

    public function test_download_pricelist_filename_and_type_as_Vet(){
        $this->download_pricelist_filename_and_type('Vet');
    }

    public function test_download_pricelist_filename_and_type_as_VetAide(){
        $this->download_pricelist_filename_and_type('VetAide');
    }
   
    public function test_download_pricelist_filename_and_type_as_IHealthContentManager(){
        $this->download_pricelist_filename_and_type('IHealthContentManager');
    }

    public function test_download_pricelist_filename_and_type_as_IFodderContentManager(){
        $this->download_pricelist_filename_and_type('IFodderContentManager');
    }
    
    public function test_download_pricelist_filename_and_type_as_ReportsUser(){
        $this->download_pricelist_filename_and_type('ReportsUser');
    }   

    //For download, we can check if the file type is correct, and the filename matches the custom filename based on srp_year
    public function download_pricelist_filename_and_type(String $role)
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);   
    
        //Create 2 ingredients under 1 category
        $category1 = Category::factory()
                    ->has(Ingredient::factory()->count(2))
                    ->create();   

        $srpYear1 = SrpYear::factory()->create();

        $ingredientsList = Ingredient::all(); 

        //SRP Year 1 Pricing
        $IngredientSrpYear1A = $this->createIngredientSrpYear($ingredientsList->get(0)->id, $srpYear1->id, 10);
        $IngredientSrpYear1B = $this->createIngredientSrpYear($ingredientsList->get(1)->id, $srpYear1->id, 20);
        
        $expected_filename = '['.$srpYear1->year.'] Price List.csv';
        $url = '/'.$data['role'].'/DownloadPriceBySrp?srpyear_id='.$srpYear1->id.'&srp_name='.$srpYear1->year;

        ob_start(); 

        $response = $this->actingAs($user)->get($url);

        //check file type
        $content_type = $response->headers->get('content-type');
        $this->assertEquals($content_type, "text/csv" );

        //check file name
        $header = $response->headers->get('content-disposition');
        $this->assertEquals($header, "attachment; filename=\"".$expected_filename."\"" );
        
        ob_get_clean();

    }


    public function test_createSrpYear_validation_required(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSrpYear', 
                                    [ 

                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['year' => 'The year field is required.']);
        
        ob_get_clean();  
    }  


    public function test_createSrpYear_format_check(){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSrpYear', 
                                    [ 
                                        'year' => 'No Year Number',
                                    ]        
                                    ); 

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('add', ['year' => 'The year format is invalid.']);
        
        ob_get_clean();  
    }  


    public function test_createSrpYearAsAdmin()
    {
        $this->createSrpYear_valid_role('Admin');
    }

    public function test_createSrpYearAsIFodderContentManager()
    {
        $this->createSrpYear_valid_role('IFodderContentManager');
    }   


    public function createSrpYear_valid_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSrpYear', 
                                    [ 
                                        'year' => '2022 Nueva Ecija'
                                    ]        
                                    ); 

    
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        //Check if record is inserted
        $srpYear = SrpYear::where('id', '1')->get()->first();
        $this->assertEquals('2022 Nueva Ecija', $srpYear->year);
        $this->assertEquals(0, $srpYear->is_active);
        
        ob_get_clean();  
    }  
    

    
    public function test_createSrpYearAs_blocked_Vet()
    {
        $this->createSrpYear_blocked_role('Vet');
    }   

    public function test_createSrpYearAs_blocked_VetAide()
    {
        $this->createSrpYear_blocked_role('VetAide');
    }   
 
    public function test_createSrpYearAs_blocked_IHealthContentManager()
    {
        $this->createSrpYear_blocked_role('IHealthContentManager');
    }      
    
    public function test_createSrpYearAs_blocked_ReportsUser()
    {
        $this->createSrpYear_blocked_role('ReportsUser');
    }  

    public function createSrpYear_blocked_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    
       $category = Category::factory()->create();

       $this->actingAs($user)->get('/'.$data['role'].'/AdminPricing');
    
       $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSrpYear', 
                                    [ 
                                        'year' => '2022 Nueva Ecija'
                                    ]        
                                    ); 

    
       $response->assertStatus(404);

        //Check if record is NOT inserted
        $srpYear = SrpYear::where('id', '1')->get()->first();
        $this->assertNull($srpYear);
        
        ob_get_clean();  
    }  
    

    public function test_updateSrpYearActivation_As_Admin()
    {
        $this->updateSrpYearActivation_valid_role('Admin');
    }

   public function test_updateSrpYearActivation_As_IFodderContentManager()
    {
        $this->updateSrpYearActivation_valid_role('IFodderContentManager');
    }
   

    public function updateSrpYearActivation_valid_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    
       $category = Category::factory()->create();
       $srpYear = SrpYear::factory()->create();
       $this->assertNotEquals('1', $srpYear->is_active);

       $response = $this->actingAs($user)->json('post', '/'.$data['role'].'/ActivateSrpYear', 
                                    [ 
                                        'srp_year_id' => '1',
                                        'is_active' => '1',
                                    ]        
                                    ); 
    
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        //Check if record has updated status
        $srpYearResult = SrpYear::where('id', '1')->get()->first();
        $this->assertEquals('1', $srpYearResult->is_active);
        
        ob_get_clean();  
    }  


    public function test_updateSrpYearActivation_blocked_Vet()
    {
        $this->updateSrpYearActivation_blocked_role('Vet');
    }      

    public function test_updateSrpYearActivation_blocked_VetAide()
    {
        $this->updateSrpYearActivation_blocked_role('VetAide');
    }  

    public function test_updateSrpYearActivation_blocked_IHealthContentManager()
    {
        $this->updateSrpYearActivation_blocked_role('IHealthContentManager');
    }  
    
    public function test_updateSrpYearActivation_blocked_ReportsUser()
    {
        $this->updateSrpYearActivation_blocked_role('ReportsUser');
    }     


 
    public function updateSrpYearActivation_blocked_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
    
       $category = Category::factory()->create();
       $srpYear = SrpYear::factory()->create();
       $this->assertNotEquals('1', $srpYear->is_active);

       $response = $this->actingAs($user)->json('post', '/'.$data['role'].'/ActivateSrpYear', 
                                    [ 
                                        'srp_year_id' => '1',
                                        'is_active' => '1',
                                    ]        
                                    ); 
    
        $response->assertStatus(404);

        //Check if record has NOT updated status
        $srpYearResult = SrpYear::where('id', '1')->get()->first();
        $this->assertNotEquals('1', $srpYearResult->is_active);
    
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
