<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\AdminLevelTwo;

use App\Models\UserAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAdminLevelTwoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\UserAdminLevelTwo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'admin_level_two_id' => AdminLevelTwo::factory()
        ];
    }
}
