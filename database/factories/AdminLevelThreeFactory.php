<?php

namespace Database\Factories;

use App\Models\AdminLevelThree;
use App\Models\AdminLevelTwo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class AdminLevelThreeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\AdminLevelThree::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'admin_level_two_id' => AdminLevelTwo::factory(),

        ];
    }
}
