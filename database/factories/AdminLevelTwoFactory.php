<?php

namespace Database\Factories;

use App\Models\AdminLevelOne;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class AdminLevelTwoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\AdminLevelTwo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->state(),
            'admin_level_one_id' => AdminLevelOne::factory(),

        ];
    }
}
