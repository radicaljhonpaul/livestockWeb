<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\User;
use App\Models\UserRoles;

use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_invalid_credentials_based_on_role_vet()
    {
        $this->login_invalid_credentials_based_on_role('Vet');
    }

    public function test_login_invalid_credentials_based_on_role_vetaide()
    {
        $this->login_invalid_credentials_based_on_role('VetAide');
    }

    public function test_login_invalid_credentials_based_on_role_IHealthContentManager()
    {
        $this->login_invalid_credentials_based_on_role('IHealthContentManager');
    }

    public function test_login_invalid_credentials_based_on_role_IFodderContentManager()
    {
        $this->login_invalid_credentials_based_on_role('IFodderContentManager');
    }

    public function test_login_invalid_credentials_based_on_role_Admin()
    {
        $this->login_invalid_credentials_based_on_role('Admin');
    }

        public function test_login_invalid_credentials_based_on_role_ReportsUser()
    {
        $this->login_invalid_credentials_based_on_role('ReportsUser');
    }

    public function login_invalid_credentials_based_on_role(String $role)
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create(['id' => 3, 'email' => 'email3@gmail.com']);
        $data = UserRoles::factory()->create([ 'user_id' => 3,'role' => $role]);

        $response = $this->actingAs($user)->post('api/login',  [
            'email' => "email1@gmail.com",
            'password' => "password1"
        ]);
        $response->assertStatus(401);

    }

    //  Test for Login
    public function test_login_valid_credentials_user_role_vet()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create(['id' => 1, 'email' => 'email@gmail.com']);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'Vet']);

        $response = $this->actingAs($user)->post('api/login',  [
            'email' => "email@gmail.com",
            'password' => "password"
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('user')
            ->has('role')
            ->has('token')
        );

        // return dd($response);
    }

    public function test_login_valid_credentials_user_role_vetaide()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create(['id' => 1, 'email' => 'email@gmail.com']);
        $data = UserRoles::factory()->create([ 'user_id' => 1,'role' => 'VetAide']);

        $response = $this->actingAs($user)->post('api/login',  [
            'email' => "email@gmail.com",
            'password' => "password"
        ]);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('user')
            ->has('role')
            ->has('token')
        );

        // return dd($response);
    }

    public function test_login_valid_credentials_user_role_IHealthContentManager()
    {
        $this->login_valid_credentials_user_role_not_vet('IHealthContentManager');
    }

    public function test_login_valid_credentials_user_role_IFodderContentManager()
    {
        $this->login_valid_credentials_user_role_not_vet('IFodderContentManager');
    }

    public function test_login_valid_credentials_user_role_Admin()
    {
        $this->login_valid_credentials_user_role_not_vet('Admin');
    }

    public function test_login_valid_credentials_user_role_ReportsUser()
    {
        $this->login_valid_credentials_user_role_not_vet('ReportsUser');
    }

    public function login_valid_credentials_user_role_not_vet(String $role)
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create(['id' => 2, 'email' => 'email@gmail.com']);
        $data = UserRoles::factory()->create([ 'user_id' => 2,'role' => $role]);

        $response = $this->actingAs($user)->post('api/login',  [
            'email' => "email@gmail.com",
            'password' => "password"
        ]);
        
        $response->assertStatus(401);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
            ->where('message', 'Unable to login, assigned roles should either Vet or Vet Aide. Please contact your Administrator regarding this error.')
        );
    }
    
}
