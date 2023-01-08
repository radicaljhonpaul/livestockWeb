<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\AdminLevelTwo;
use App\Models\UserAssignment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;

use Tests\TestCase;

class UserAdminLevelTwoTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_assignment_has_expected_columns()
    {
        $this->withoutExceptionHandling();
        $this->assertTrue(Schema::hasColumn('user_admin_level_twos','user_id'), 1);
        $this->assertTrue(Schema::hasColumn('user_admin_level_twos','admin_level_two_id'), 1);
    }

    public function test_user_belongs_to_many_admin_level_two()
    {

        $user = User::factory()
        ->has(AdminLevelTwo::factory()->count(3))
        ->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->adminleveltwos);
        $this->assertEquals(3, $user->adminleveltwos->count());

    }


    public function test_admin_level_two_belongs_to_many_users()
    {

        $level2 = AdminLevelTwo::factory()
        ->has(User::factory()->count(3))
        ->create();


        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $level2->users);
        $this->assertEquals(3, $level2->users->count());

    }



}
