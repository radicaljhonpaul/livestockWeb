<?php

namespace Tests\Features\Http\Controllers\Api;

use App\Models\User;
use App\Models\Livestock;
use App\Models\AdminLevelTwo;
use App\Models\UserAdminLevelTwo;
use App\Models\DiagnosisLog;
use App\Models\Farmer;
use App\Models\FeedingLog;
use App\Models\UserRoles;
use App\Models\VisitLog;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

class FarmerControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_getFarmersAssigned_base_structure_no_assigned()
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        //check if api endpoint is existing
        $response = $this->actingAs($user)->get('api/getFarmersAssigned');
        $response->assertStatus(Response::HTTP_OK);


        $livestock = Livestock::factory()->create();
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has('admin_level_ones')
                 ->has('farmers')
                 ->etc()
        );

        //dd($response);
    }

    public function test_getFarmersAssigned_base_structure_assigned_one()
    {
    

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $livestock = Livestock::factory()->create();

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        

        $response = $this->actingAs($user)->get('api/getFarmersAssigned');
        //check if api endpoint is existing
        $response->assertStatus(Response::HTTP_OK);

        
        //check structure for admin_level_one
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has('admin_level_ones')
                 ->where('admin_level_ones.0.id', $livestock->farmer->adminLevelThree->adminLevelTwo->adminLevelOne->id)   
                 ->where('admin_level_ones.0.name', $livestock->farmer->adminLevelThree->adminLevelTwo->adminLevelOne->name)
                 ->where('admin_level_ones.0.admin_level_twos.0.id', $livestock->farmer->adminLevelThree->adminLevelTwo->id)    
                 ->where('admin_level_ones.0.admin_level_twos.0.name', $livestock->farmer->adminLevelThree->adminLevelTwo->name)
                 ->where('admin_level_ones.0.admin_level_twos.0.admin_level_one_id', strval($livestock->farmer->adminLevelThree->adminLevelTwo->adminLevelOne->id))
                 ->where('admin_level_ones.0.admin_level_twos.0.admin_level_threes.0.id', $livestock->farmer->adminLevelThree->id)
                 ->where('admin_level_ones.0.admin_level_twos.0.admin_level_threes.0.name', strval($livestock->farmer->adminLevelThree->name))
                 ->where('admin_level_ones.0.admin_level_twos.0.admin_level_threes.0.admin_level_two_id', strval($livestock->farmer->adminLevelThree->adminLevelTwo->id))
                 ->etc()

        );

        //check structure for farmers
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmers')
                ->where('farmers.0.id', $livestock->farmer->id)   
                ->where('farmers.0.pcc_system_id', $livestock->farmer->pcc_system_id)
                ->where('farmers.0.last_name', $livestock->farmer->last_name)    
                ->where('farmers.0.first_name', $livestock->farmer->first_name)   
                ->where('farmers.0.gender', $livestock->farmer->gender) 
                ->where('farmers.0.birthdate', $livestock->farmer->birthdate)
                ->where('farmers.0.mobile_number', $livestock->farmer->mobile_number)
                ->where('farmers.0.phone_number', $livestock->farmer->phone_number)
                ->where('farmers.0.fb_profile', $livestock->farmer->fb_profile)
                ->where('farmers.0.lat', $livestock->farmer->lat)
                ->where('farmers.0.longitude', $livestock->farmer->longitude)
                ->where('farmers.0.admin_level_three_id', $livestock->farmer->admin_level_three_id)
                ->etc()
        );

        //check structure for livestock
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmers')
                ->where('farmers.0.livestocks.0.id', $livestock->id)
                ->where('farmers.0.livestocks.0.carabao_code', $livestock->carabao_code)
                ->where('farmers.0.livestocks.0.breed', $livestock->breed)
                ->where('farmers.0.livestocks.0.sex', $livestock->sex)
                ->where('farmers.0.livestocks.0.year_of_birth', $livestock->year_of_birth)
                ->where('farmers.0.livestocks.0.registration_date', $livestock->registration_date)
                ->has('farmers.0.livestocks.0.is_pregnant')
                ->where('farmers.0.livestocks.0.farmer_id', strval($livestock->farmer_id))
                ->etc()
        );        

        //dd($response);
    }

    /**
     * Note: if this method fails due to UNIQUE constrain violation, just run the test again. We have set admin level one to unique
     * and sometimes the factory faker assigns the same name
     */
    public function test_getFarmersAssigned_visibility_assigned_two_out_of_three_locations()
    {
    

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $livestock = Livestock::factory()->create();
        $livestockb = Livestock::factory()->create();
        $livestockc = Livestock::factory()->create();

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();

        $userAssignation2 = new UserAdminLevelTwo;
        $userAssignation2->user_id = $user->id;
        $userAssignation2->admin_level_two_id = $livestockb->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation2->save();
        
        //create one extra admin level which is not associated with user
        $extraAdminLevelTwo = AdminLevelTwo::factory()->create();

        $response = $this->actingAs($user)->get('api/getFarmersAssigned');
        //check if api endpoint is existing
        $response->assertStatus(Response::HTTP_OK);

        
        //check visibility should only be to two admin level ones w/ 1 admin level two each
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has('admin_level_ones')
                 ->has('admin_level_ones.0.id')   
                 ->has('admin_level_ones.1.id') 
                 ->missing('admin_level_ones.2.id')
                 ->missing('admin_level_ones.0.admin_level_twos.1.name')   
                 ->missing('admin_level_ones.1.admin_level_twos.1.name')   
                 ->etc()

        );

        //check structure for farmers
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmers')
                ->has('farmers.0.id')   
                ->has('farmers.1.id')
                ->missing('farmers.2.id')
                ->etc()
        );

        
        //check structure for livestock
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmers')
                ->has('farmers.0.livestocks.0.id')   
                ->has('farmers.1.livestocks.0.id')
                ->missing('farmers.0.livestocks.1.id')
                ->missing('farmers.1.livestocks.1.id')
                ->etc()
        );        

 }   

    public function test_getFarmersAssigned_not_logged_in()
    {
        //no logged in user
        $response = $this->json('get', 'api/getFarmersAssigned');    
        $response->assertStatus(401);
        
    }

    public function test_getSpecificFarmerAssigned_not_logged_in()
    {
        
        //no logged in user
        $response = $this->json('post', 'api/getSpecificFarmerAssigned');    
        $response->assertStatus(401);
        
    }


    public function test_getSpecificFarmerAssigned_base_structure_no_assigned()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        //check if api endpoint is existing
        $response = $this->actingAs($user)->post('api/getSpecificFarmerAssigned');
        $response->assertStatus(Response::HTTP_OK);

        $livestock = Livestock::factory()->create();
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json->has('farmer')
                 ->etc()
        );

        //dd($response);
    }

    public function test_getSpecificFarmerAssigned_base_structure_assigned_one_no_module()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);

        $livestock = Livestock::factory()->create();

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        

        $response = $this->actingAs($user)->post('api/getSpecificFarmerAssigned',  ['farmer_id' => $livestock->farmer->id ] );
        //check if api endpoint is existing

        $response->assertStatus(Response::HTTP_OK);

        
        //check structure for farmers
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmer')
                ->missing('farmers')
                ->where('farmer.0.id', $livestock->farmer->id)   
                ->where('farmer.0.pcc_system_id', $livestock->farmer->pcc_system_id)
                ->where('farmer.0.last_name', $livestock->farmer->last_name)    
                ->where('farmer.0.first_name', $livestock->farmer->first_name)   
                ->where('farmer.0.gender', $livestock->farmer->gender) 
                ->where('farmer.0.birthdate', $livestock->farmer->birthdate)
                ->where('farmer.0.mobile_number', $livestock->farmer->mobile_number)
                ->where('farmer.0.phone_number', $livestock->farmer->phone_number)
                ->where('farmer.0.fb_profile', $livestock->farmer->fb_profile)
                ->where('farmer.0.lat', $livestock->farmer->lat)
                ->where('farmer.0.longitude', $livestock->farmer->longitude)
                ->where('farmer.0.admin_level_three_id', $livestock->farmer->admin_level_three_id)
                ->missing('farmer.1.id')
                ->etc()
        );

        //check structure for livestock
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmer')
                ->where('farmer.0.livestocks.0.id', $livestock->id)
                ->where('farmer.0.livestocks.0.carabao_code', $livestock->carabao_code)
                ->where('farmer.0.livestocks.0.breed', $livestock->breed)
                ->where('farmer.0.livestocks.0.sex', $livestock->sex)
                ->where('farmer.0.livestocks.0.year_of_birth', $livestock->year_of_birth)
                ->where('farmer.0.livestocks.0.registration_date', $livestock->registration_date)
                ->has('farmer.0.livestocks.0.is_pregnant')
                ->where('farmer.0.livestocks.0.farmer_id', strval($livestock->farmer_id))
                ->etc()
        );        
        

        //dd($response);
    }


    public function test_getSpecificFarmerAssigned_base_structure_assigned_one_diagnosis_module()
    {

        $this->withoutExceptionHandling();
        
        $user = User::factory()->create(['id' => 1]);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
    
        $livestock = Livestock::factory()->has(DiagnosisLog::factory()->count(2))->create();

        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $livestock->farmer->adminLevelThree->adminLevelTwo->id;
        $userAssignation->save();
        

        $response = $this->actingAs($user)->post('api/getSpecificFarmerAssigned',  ['farmer_id' => $livestock->farmer->id, 'module' => 'diagnosis' ] );
        //check if api endpoint is existing
        $response->assertStatus(Response::HTTP_OK);

        
        //check structure for farmers
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmer')
                ->missing('farmers')
                ->where('farmer.0.id', $livestock->farmer->id)   
                ->where('farmer.0.pcc_system_id', $livestock->farmer->pcc_system_id)
                ->where('farmer.0.last_name', $livestock->farmer->last_name)    
                ->where('farmer.0.first_name', $livestock->farmer->first_name)   
                ->where('farmer.0.gender', $livestock->farmer->gender) 
                ->where('farmer.0.birthdate', $livestock->farmer->birthdate)
                ->where('farmer.0.mobile_number', $livestock->farmer->mobile_number)
                ->where('farmer.0.phone_number', $livestock->farmer->phone_number)
                ->where('farmer.0.fb_profile', $livestock->farmer->fb_profile)
                ->where('farmer.0.lat', $livestock->farmer->lat)
                ->where('farmer.0.longitude', $livestock->farmer->longitude)
                ->where('farmer.0.admin_level_three_id', $livestock->farmer->admin_level_three_id)
                ->missing('farmer.1.id')
                ->etc()
        );

        //check structure for livestock
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmer')
                ->where('farmer.0.livestocks.0.id', $livestock->id)
                ->where('farmer.0.livestocks.0.carabao_code', $livestock->carabao_code)
                ->where('farmer.0.livestocks.0.breed', $livestock->breed)
                ->where('farmer.0.livestocks.0.sex', $livestock->sex)
                ->where('farmer.0.livestocks.0.year_of_birth', $livestock->year_of_birth)
                ->where('farmer.0.livestocks.0.registration_date', $livestock->registration_date)
                ->has('farmer.0.livestocks.0.is_pregnant')
                ->where('farmer.0.livestocks.0.farmer_id', strval($livestock->farmer_id))
                ->etc()
        );        
        
        //check structure for additional diagnosis logs
        $response->assertJson(fn (AssertableJson $json) =>
                $json->has('farmer')
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.id', $livestock->diagnosisLogs[0]->id)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.visit_date', $livestock->diagnosisLogs[0]->visit_date)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.activity_type', $livestock->diagnosisLogs[0]->activity_type)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.status', $livestock->diagnosisLogs[0]->status)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.assessment', $livestock->diagnosisLogs[0]->assessment)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.notes', $livestock->diagnosisLogs[0]->notes)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.created_by', $livestock->diagnosisLogs[0]->created_by)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.authorized_by', $livestock->diagnosisLogs[0]->authorized_by)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.assigned_to', $livestock->diagnosisLogs[0]->assigned_to)
                ->where('farmer.0.livestocks.0.diagnosis_logs.0.livestock_id', strval($livestock->id))
                ->has('farmer.0.livestocks.0.diagnosis_logs.1.livestock_id')
                ->etc()
        );   

        //check structure for additional related info
        $response->assertJson(fn (AssertableJson $json) =>
                $json
                ->has('farmer.0.livestocks.0.diagnosis_logs.0.symptoms')
                ->has('farmer.0.livestocks.0.diagnosis_logs.0.health_conditions')
                ->has('farmer.0.livestocks.0.diagnosis_logs.0.interventions')
                ->has('farmer.0.livestocks.0.pregnancies')
                ->etc()
        );         


        //dd($response);
    }


}
