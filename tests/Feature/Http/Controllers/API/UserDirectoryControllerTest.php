<?php

namespace Tests\Features\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserAdminLevelTwo;
use App\Models\AdminLevelTwo;
use App\Models\UserRoles;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

class UserDirectoryControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_getAreaUsers_not_logged_in()
    {
        //no logged in user
        $response = $this->json('get', 'api/getAreaUsers');    
        $response->assertStatus(401);
        
    }

    public function test_getAreaUsers_base_structure_no_assigned()
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
    
        //check if api endpoint is existing
        $response = $this->actingAs($user)->get('api/getAreaUsers');
        $response->assertStatus(Response::HTTP_OK);


        $adminLevelTwo = AdminLevelTwo::factory()->create();
        
        $response->assertJson(fn (AssertableJson $json) =>
                 $json
                 ->has('users')
                 ->missing('users.0.id')
                 ->etc()
        );

        //dd($response);
    }



    public function test_getAreaUsers_base_structure_assigned_vetAide(){

        $this->getAreaUsers_base_structure_assigned_valid_role('VetAide');

    }

    public function test_getAreaUsers_base_structure_assigned_vet(){

        $this->getAreaUsers_base_structure_assigned_valid_role('Vet');

    }


    public function getAreaUsers_base_structure_assigned_valid_role(string $role)
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        UserRoles::factory()->create([ 'user_id' => $user->id, 'role' => 'Admin']);

        $vetUser = User::factory()->create();
        UserRoles::factory()->create([ 'user_id' => $vetUser->id, 'role' => $role]);

        $adminLevelTwoA = AdminLevelTwo::factory()->create();
        $adminLevelTwoB = AdminLevelTwo::factory()->create();

        
        //Assign Same Location to user and vet user (Admin Level Two A)
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $adminLevelTwoA->id;
        $userAssignation->save();

        $userAssignationb = new UserAdminLevelTwo;
        $userAssignationb->user_id = $vetUser->id;
        $userAssignationb->admin_level_two_id = $adminLevelTwoA->id;
        $userAssignationb->save();


        //check if api endpoint is existing
        $response = $this->actingAs($user)->get('api/getAreaUsers');
        $response->assertStatus(Response::HTTP_OK);

        //Vet User in same are should be returned
        $response->assertJson(fn (AssertableJson $json) =>
                 $json
                 ->has('users')
                 ->where('users.0.id',  $vetUser->id)
                 ->where('users.0.last_name',  $vetUser->last_name)
                 ->where('users.0.first_name',  $vetUser->first_name)
                 ->where('users.0.mobile_number',  $vetUser->mobile_number)
                 ->where('users.0.user_roles.0.user_id',  strval($vetUser->id))
                 ->where('users.0.user_roles.0.role',  $role)
                 ->missing('users.1.id')        //only 1 user should be returned (vet role)
                 ->etc()
        );

    }


    public function test_getAreaUsers_other_area_no_visibility_vet(){
        $this->getAreaUsers_other_area_no_visibility('Vet');
    }

    public function test_getAreaUsers_other_area_no_visibility_vetaide(){
        $this->getAreaUsers_other_area_no_visibility('VetAide');
    }



    public function getAreaUsers_other_area_no_visibility(string $role)
    {
    
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        UserRoles::factory()->create([ 'user_id' => $user->id, 'role' => 'Admin']);

        $vetUser = User::factory()->create();
        UserRoles::factory()->create([ 'user_id' => $vetUser->id, 'role' => $role]);

        $adminLevelTwoA = AdminLevelTwo::factory()->create();
        $adminLevelTwoB = AdminLevelTwo::factory()->create();

        
        //Assign Same Location to user and vet user (Admin Level Two A)
        $userAssignation = new UserAdminLevelTwo;
        $userAssignation->user_id = $user->id;
        $userAssignation->admin_level_two_id = $adminLevelTwoA->id;
        $userAssignation->save();

        $userAssignationb = new UserAdminLevelTwo;
        $userAssignationb->user_id = $vetUser->id;
        $userAssignationb->admin_level_two_id = $adminLevelTwoB->id;
        $userAssignationb->save();


        //check if api endpoint is existing
        $response = $this->actingAs($user)->get('api/getAreaUsers');
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
                 $json
                 ->has('users')
                 ->missing('users.0.id')
                 ->etc()
        );

    }



}