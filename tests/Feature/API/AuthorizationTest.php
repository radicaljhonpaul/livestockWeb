<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;


class AuthorizationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    /** @test */
    public function login_existing_user()
    {
        $this->withoutExceptionHandling();
        $user = User::create([
            'name' => 'Juan Animal',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        // dd($user);
        
        // Post Request for our Endpoint
        $response = $this->post('/api/login', [
            'email' => 'juan@gmail.com',
            'password' => 'secret',
        ]);

        // Response
        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
        
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'iphone',
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id
        ]);
    }

    /** @test */
    public function get_user_from_token()
    {
        $user = User::create([
            'name' => 'Juan Animal',
            'email' => 'juan@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $token = $user->createToken('iphone')->plainTextToken;

        $response = $this->get('/api/user', [
            'Authorization' => 'Bearer '.$token
        ]);

        $response->assertSuccessful();
        $response->assertJson(function ($json){
            $json->where('email', 'juan@gmail.com')
            ->missing('password')
            ->etc();
        });
    }


    /** @test */
    public function register_new_user()
    {
        $response = $this->post('api/registration', [
            'name' => 'Juan Animal',
            'email' => 'juan@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'office',
        ]);

        $response->assertSuccessful();

        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('users', ['email' => 'juan@gmail.com']);
        $this->assertDatabaseHas('personal_access_tokens', ['name' => 'iphone']);
    }

    /** @test */
    public function logout_user()
    {
        $user = User::create([
            'name' => 'Juan Animal',
            'email' => 'juan@gmail.com',
            'password' => bcrypt(request('password')),
        ]);

        $token = $user->createToken('iphone')->plainTextToken;

        $response = $this->post('/api/logout', [], [
            'Authorization' => 'Bearer '.$token
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
