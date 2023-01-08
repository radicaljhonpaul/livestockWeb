<?php

namespace Tests\Feature\Http\Controllers\HealthGuide;

use App\Models\HealthCondition;
use App\Models\User;
use App\Models\OrganSystem;
use App\Models\Symptom;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use phpDocumentor\Reflection\Types\Null_;

use Tests\TestCase;

class HealthGuidesControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test Page Rendered For IHealth
    */

    // Start for redering pages
    public function test_page_rendered_for_ihealth_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Systems')
            ->has('OrganSystems', 1)
            ->where('OrganSystems.0.id', $orgsystem1->id)
            ->where('OrganSystems.0.name', $orgsystem1->name)
            ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_page_rendered_for_ihealth_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Systems')
            ->has('OrganSystems', 1)
            ->where('OrganSystems.0.id', $orgsystem1->id)
            ->where('OrganSystems.0.name', $orgsystem1->name)
            ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_page_rendered_for_ihealth_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Systems')
            ->has('OrganSystems', 1)
            ->where('OrganSystems.0.id', $orgsystem1->id)
            ->where('OrganSystems.0.name', $orgsystem1->name)
            ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_page_rendered_for_ihealth_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Systems')
            ->has('OrganSystems', 1)
            ->where('OrganSystems.0.id', $orgsystem1->id)
            ->where('OrganSystems.0.name', $orgsystem1->name)
            ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_page_rendered_for_ihealth_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');

        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Systems')
            ->has('OrganSystems', 1)
            ->where('OrganSystems.0.id', $orgsystem1->id)
            ->where('OrganSystems.0.name', $orgsystem1->name)
            ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_page_rendered_for_ihealth_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

          // Create OrganSystem
          $orgsystem1 = OrganSystem::factory()->create();

          // Hit HealthGuides/Systems Page
          $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminHealthGuide');
  
          // Check for status 200
          $response->assertStatus(200);
  
          $response->assertInertia(fn ($page) => $page
              ->component('HealthGuides/Systems')
              ->has('OrganSystems', 1)
              ->where('OrganSystems.0.id', $orgsystem1->id)
              ->where('OrganSystems.0.name', $orgsystem1->name)
              ->where('OrganSystems.0.local_term', $orgsystem1->local_term)
          );
          
          ob_get_clean();
    }
    // End for redering pages

    // Start for view specific system
    public function test_user_can_view_specific_system_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);
    
        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_view_specific_system_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
    
        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_view_specific_system_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
    
        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_view_specific_system_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_view_specific_system_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_view_specific_system_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);
    
      // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificSystem', [
            'name' => 'Reproductive',
            'id' => 1
        ]);

        $response->assertStatus(200);
        ob_get_clean();
    }
    // End for view specific system

    // Start for view symptoms list
    public function test_user_can_view_symptoms_list_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_list_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_list_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_list_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_list_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_list_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        Symptom::factory()
        ->count(3)
        ->create();

        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);

        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 3)
        );
        
        ob_get_clean();
    }
    // End for view symptoms list

    // Start for view symptoms all columns list
    public function test_user_can_view_symptoms_all_columns_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_all_columns_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_all_columns_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_all_columns_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_all_columns_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }

    public function test_user_can_view_symptoms_all_columns_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

        // Create Symptoms
        $symptom1 = Symptom::factory()->create();
        // Hit HealthGuides/Symptoms Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/Symptoms');

        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('HealthGuides/Symptoms')
            ->has('AllSymptoms', 1)
            ->where('AllSymptoms.0.id', $symptom1->id)
            ->where('AllSymptoms.0.name', $symptom1->name)
            ->where('AllSymptoms.0.local_term', $symptom1->local_term)
        );
        
        ob_get_clean();
    }
    // End for view symptoms all columns list

    /**
     * CREATE SYMPTOMS (ALLOWED)
    */
    public function test_user_can_add_new_symptom_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSymptom',[
            'name' => 'Char',
            'local_term' => 'Char',
            'attached_media_symptoms' => [],
        ]);
        
        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_user_can_add_new_symptom_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSymptom',[
            'name' => 'Char',
            'local_term' => 'Char',
            'attached_media_symptoms' => [],
        ]);
        
        $response->assertStatus(200);
        ob_get_clean();
    }

    /**
     * EDIT SYMPTOMS (ALLOWED)
    */
    public function test_user_can_add_edit_symptom_as_admin()
    {
        // $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        $symptom1 = Symptom::factory()->create([
            'id' => 1,
            'name' => 'Sample1',
            'local_term' => 'Sample1',
        ]);
        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedSymptom',[
            'id' => 1,
            'name' => 'Sample2',
            'local_term' => 'Sample_2',
        ]);
        
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        //Check if record is inserted
        $Symptom = Symptom::where('id', '1')->get()->first();
        $this->assertEquals('Sample2', $Symptom->name);
        $this->assertEquals('Sample_2', $Symptom->local_term);
        // return dd($Symptom);

        ob_get_clean();
    }

    public function test_user_can_add_edit_symptom_as_ihealth_content_manager()
    {
        // $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        $symptom1 = Symptom::factory()->create([
            'id' => 1,
            'name' => 'Sample1',
            'local_term' => 'Sample1',
        ]);
        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedSymptom',[
            'id' => 1,
            'name' => 'Sample2',
            'local_term' => 'Sample_2',
        ]);
        
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        //Check if record is inserted
        $Symptom = Symptom::where('id', '1')->get()->first();
        $this->assertEquals('Sample2', $Symptom->name);
        $this->assertEquals('Sample_2', $Symptom->local_term);
        // return dd($Symptom);

        ob_get_clean();
    }

    /**
     * ROLES CHECK FOR CREATE SYMPTOMS (BLOCKED)
    */
    public function test_create_symptoms_valid_as_blocked_as_vet(){
        $this->create_symptoms_valid_as_blocked_as_role('Vet');

    }

    public function test_create_symptoms_valid_as_blocked_as_vetaide(){
        $this->create_symptoms_valid_as_blocked_as_role('VetAide');
    }
    
    public function test_create_symptoms_valid_as_blocked_as_ifodder_content_manager(){
        $this->create_symptoms_valid_as_blocked_as_role('IFodderContentManager');
    }

    public function test_create_symptoms_valid_as_blocked_as_reportsuser(){
        $this->create_symptoms_valid_as_blocked_as_role('ReportsUser');
    }

    public function create_symptoms_valid_as_blocked_as_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
        $user = User::factory()->create(['id' => 1]);

        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateSymptom',[
            'name' => 'Char',
            'local_term' => 'Char',
            'attached_media_symptoms' => [],
        ]);

        $response->assertStatus(404);
        
        //Check if record is NOT inserted
        $Symptom1 = Symptom::where('id', '1')->get()->first();
        $this->assertNull($Symptom1);
    }

    /**
     * ROLES CHECK FOR EDIT SYMPTOMS (BLOCKED)
    */

    public function test_edit_symptoms_valid_as_blocked_as_vet(){
        $this->edit_symptoms_valid_as_blocked_as_role('Vet');

    }

    public function test_edit_symptoms_valid_as_blocked_as_vetaide(){
        $this->edit_symptoms_valid_as_blocked_as_role('VetAide');
    }
    
    public function test_edit_symptoms_valid_as_blocked_as_ifodder_content_manager(){
        $this->edit_symptoms_valid_as_blocked_as_role('IFodderContentManager');
    }

    public function test_edit_symptoms_valid_as_blocked_as_reportsuser(){
        $this->edit_symptoms_valid_as_blocked_as_role('ReportsUser');
    }

    public function edit_symptoms_valid_as_blocked_as_role(String $role){

        //For checking validation errors, exception handling must be removed
        //$this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
        $user = User::factory()->create(['id' => 1]);

        $Symptom1 = Symptom::factory()->create([
            'id' => 1,
            'name' => 'Sample1',
            'local_term' => 'Sample1',
        ]);
        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedSymptom',[
            'id' => 1,
            'name' => 'Sample2',
            'local_term' => 'Sample_2',
        ]);
        
        $response->assertStatus(404);
        
        //Check if record is NOT inserted
        $Symptom2 = Symptom::where('name', 'Sample2')->get()->first();
        $this->assertNull($Symptom2);
        // return dd($Symptom);
    }


    /**
     * EDIT Health Conditions (ALLOWED)
    */

    public function edit_hc_valid_as_role(String $role){
       // Edit health Condition
       $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
       $user = User::factory()->create(['id' => 1]);

       $OrganSys = OrganSystem::factory()->create([
           'id' => 2
       ]);

       $HC1 = HealthCondition::factory()->create([
           'name' => "hc1",
           'organ_system_id' => 2,
           'local_term' => "hc1",
           'local_term' => "hc1",
       ]);

       $xxx = UploadedFile::fake()->image('xxx.jpg');

       $response2 = $this->actingAs($user)->post('/'.$data['role'].'/SaveEditedHC',[
           'id' => 1,
           'organ_system_id' => $HC1->organ_system_id,
           'name' => 'hc2',
           'local_term' => 'hc2',
           'attached_media_health_condition' => [$xxx]
       ]);

       $response2->assertStatus(302);
       $response2->assertSessionHasNoErrors();

       //Check if record is inserted
       $EditedHC = HealthCondition::where('id', '1')->get()->first();
       $this->assertEquals('hc2', $EditedHC->name);
       $this->assertEquals('hc2', $EditedHC->local_term);

       ob_end_flush(); 
    }
    

}