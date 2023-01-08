<?php

namespace Tests\Feature\Http\Controllers\Farmers;

use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogHealthCondition;
use App\Models\DiagnosisLogIntervention;
use App\Models\DiagnosisLogSymptom;
use App\Models\Farmer;
use App\Models\FeedingLog;
use App\Models\Livestock;
use App\Models\User;
use App\Models\VisitLog;
use App\Models\Ingredient;
use App\Models\FeedingLogIngredient;
use App\Models\HealthCondition;
use App\Models\Intervention;
use App\Models\MediaDiagnosisLog;
use App\Models\Symptom;
use App\Models\UserAdminLevelTwo;
use App\Models\UserRoles;
use App\Models\AdminLevelTwo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class FarmersControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Page Rendered For Farmers
    */
    /** @test */
    public function test_page_rendered_for_farmers_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);

        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }

    public function test_page_rendered_for_farmers_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }

    public function test_page_rendered_for_farmers_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }

    public function test_page_rendered_for_farmers_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }

    public function test_page_rendered_for_farmers_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }
    
    public function test_page_rendered_for_farmers_as_reports_user()
    {
        $this->withoutExceptionHandling();
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $farmer = Farmer::factory()->create();
        
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminFarmers');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersList')
            ->has('FarmersList')
        );

        ob_get_clean();
    }

    /**
     * Specific Farmers
    */
    public function test_view_specific_farmers_as_admin()
    {
        $this->withoutExceptionHandling();
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    public function test_view_specific_farmers_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    public function test_view_specific_farmers_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    public function test_view_specific_farmers_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    public function test_view_specific_farmers_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    public function test_view_specific_farmers_as_reports_user()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);
    
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificFarmer', [
            'id' => '1',
        ]);
        
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/FarmersProfile')
            ->has('FarmerDetails')
        );

        ob_get_clean();
    }

    /**
     * Test View Livestock
    */
    public function test_view_livestock_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }

    public function test_view_livestock_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }
    

    public function test_view_livestock_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }

    public function test_view_livestock_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }

    public function test_view_livestock_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }

    public function test_view_livestock_as_reports_user()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);
        
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock');

        // Check for status 200
        $response->assertStatus(200);
        
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails')
        );

        ob_get_clean();
    }



    /**
     * Note: If this test fails due to UNIQUE constraint on admin_level_ones.name, run the test again
     * as dummy data might have created same name for admin_level_one
     */
    public function view_visit_visitLog_diagnosis_log(String $role){

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $visit_logs->livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();

        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 1)
            ->has('LivestockDetails.data.0.carabao_code')
            ->has('LivestockDetails.data.0.visit_logs', 1)
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.livestock')
            //check for specific queried fields on select
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.assigned_to_id')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.assigned_to_first_name')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.assigned_to_last_name')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.joined_farmers_id')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.joined_farmers_last_name')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.joined_farmers_first_name')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.joined_farmers_mobile_number')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.farmer_id')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.assigned_to')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.joined_livestock_id')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.farmer_id')
            ->has('LivestockDetails.data.0.visit_logs.0.diagnosis_log.carabao_code')
        );

        ob_get_clean();

    }


    public function test_view_visitLog_diagnosis_log_fields_as_admin()
    {
        $this->view_visit_visitLog_diagnosis_log('Admin');

    }

    
    public function test_view_visitLog_diagnosis_log_fields_as_vet()
    {
        $this->view_visit_visitLog_diagnosis_log('Vet');
    }

    public function test_view_visitLog_diagnosis_log_fields_as_vetaide()
    {
        $this->view_visit_visitLog_diagnosis_log('VetAide');
    }
    
    public function test_view_visitLog_diagnosis_log_fields_as_ifodder_content_manager_unassigned()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();
  
        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 0)
        );

        ob_get_clean();
    }

    public function test_view_visitLog_diagnosis_log_fields_as_ihealth_content_manager_unassigned()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();
  
        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 0)
        );

        ob_get_clean();
    }

    public function test_view_visitLog_diagnosis_log_fields_as_reports_user()
    {
        $this->view_visit_visitLog_diagnosis_log('ReportsUser');
    }

    /**
     * Note: If this test fails due to UNIQUE constraint on admin_level_ones.name, run the test again
     * as dummy data might have created same name for admin_level_one
     */

    public function view_visitLog_feeding_log_fields(String $role){
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => $role]);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $feedLogIng = new FeedingLogIngredient;
        $feedLogIng->feeding_log_id = $visit_logs->feeding_log_id;
        $feedLogIng->ingredient_id = $ingredient->id;
        $feedLogIng->save();

        //assign users
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $visit_logs->livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();

        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 1)
            ->has('LivestockDetails.data.0.carabao_code')
            ->has('LivestockDetails.data.0.visit_logs', 1)
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.id')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.ingredient_id')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.ingredient_name')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.category_name')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.pivot')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.pivot.quantity')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.ingredients.0.pivot.price_at_date')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.created_by')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.created_by.id')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.created_by.first_name')
            ->has('LivestockDetails.data.0.visit_logs.0.feeding_log.created_by.last_name')
            ->has('LivestockDetails.data.0.visit_logs.0.assigned_to')
        );

        ob_get_clean();


    }

    public function test_view_visitLog_feeding_log_fields_as_admin()
    {
       $this->view_visitLog_feeding_log_fields('Admin');
    }

    public function test_view_visitLog_feeding_log_fields_as_vet()
    {
        $this->view_visitLog_feeding_log_fields('Vet');
    }

    public function test_view_visitLog_feeding_log_fields_as_vetaide()
    {
        $this->view_visitLog_feeding_log_fields('VetAide');
    }

    public function test_view_visitLog_feeding_log_fields_as_ihealth_content_manager_unassinged()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $feedLogIng = new FeedingLogIngredient;
        $feedLogIng->feeding_log_id = $visit_logs->feeding_log_id;
        $feedLogIng->ingredient_id = $ingredient->id;
        $feedLogIng->save();

        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 0)
        );

        ob_get_clean();
    }

    public function test_view_visitLog_feeding_log_fields_as_ifodder_content_manager_unassinged()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);
    
        // Create Farmer
        $visit_logs = VisitLog::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $feedLogIng = new FeedingLogIngredient;
        $feedLogIng->feeding_log_id = $visit_logs->feeding_log_id;
        $feedLogIng->ingredient_id = $ingredient->id;
        $feedLogIng->save();

        //Query specific livestock
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificLivestock?livestock_id='.$visit_logs->livestock_id);

        // Check for status 200
        $response->assertStatus(200);

        //Check if existing fields are being shown
        $response->assertInertia(fn ($page) => $page
            ->component('Farmers/LivestockProfile')
            ->has('LivestockDetails.data', 0)
        );

        ob_get_clean();
    }

    public function test_view_visitLog_feeding_log_fields_as_reports_user()
    {
        $this->view_visitLog_feeding_log_fields('ReportsUser');
    }

    
}
