<?php

namespace Tests\Feature\Http\Controllers\UserManagement;

use App\Models\OrganSystem;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementsControllerTest extends TestCase
{
    use RefreshDatabase;

    // Start for redering pages
    public function test_page_rendered_for_usermanagement_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 6)
            ->where('StaffList.data.1.id', $staffs1->id)
            ->where('StaffList.data.1.first_name', $staffs1->first_name)
            ->where('StaffList.data.1.last_name', $staffs1->last_name)
            ->where('StaffList.data.1.username', $staffs1->username)
            ->where('StaffList.data.1.email', $staffs1->email)
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_usermanagement_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 0)
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_usermanagement_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 0)
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_usermanagement_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 0)
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_usermanagement_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 0)
        );
        ob_get_clean();
    }

    public function test_page_rendered_for_usermanagement_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

        $staffs1 = User::factory()->create(['id' => 2]);
        $staffs2 = User::factory()->create(['id' => 3]);
        $staffs3 = User::factory()->create(['id' => 4]);
        $staffs4 = User::factory()->create(['id' => 5]);
        $staffs5 = User::factory()->create(['id' => 6]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/AdminStaff');
        // return dd($staffs5);
        // return dd($response['page']);
        // Check for status 200
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->component('Staffings/StaffsList')
            ->has('StaffList.data', 0)
        );
        ob_get_clean();
    }
    // End for redering pages


    public function test_usermanagement_specific_staff_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_usermanagement_specific_staff_as_vet()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_usermanagement_specific_staff_as_vetaide()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }
    
    
    public function test_usermanagement_specific_staff_as_ihealth_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IHealthContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }
    
    
    public function test_usermanagement_specific_staff_as_ifodder_content_manager()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'IFodderContentManager']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }

    public function test_usermanagement_specific_staff_as_reportsuser()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'ReportsUser']);
        $user = User::factory()->create(['id' => 1]);

        // Create OrganSystem
        // $orgsystem1 = OrganSystem::factory()->create();

        // Hit HealthGuides/Systems Page
        $response = $this->actingAs($user)->get('/'.$data['role'].'/SpecificStaff',  [
            'id' => 1,
        ]);
        // return dd($staffs5);
        // return dd($response['page']);

        // Check for status 200
        $response->assertStatus(200);
        ob_get_clean();
    }

    /** @test */
    public function test_usermanagement_create_staff_as_admin()
    {
        $this->withoutExceptionHandling();

        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Admin']);
        $user = User::factory()->create(['id' => 1]);

        // Hit HealthGuides/CreateSymptom Page
        $response = $this->actingAs($user)->post('/'.$data['role'].'/CreateStaff',[
            'first_name' => 'Sample',
            'last_name' => 'Sample',
            'mobile_number' => '09272677689',
            'username' => 'Sample',
            'email' => 'Sample@Sample.com',
            'password' => 'Sample@Sample.com',
            'user_role' => ['Admin', 'Vet'],
        ]);
        // check if response is redirected
        $response->assertStatus(302);
        ob_get_clean();
    }
}
