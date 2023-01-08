<?php

namespace Database\Factories;

use App\Models\HealthCondition;
use App\Models\Intervention;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterventionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Intervention::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->name(),
            'need_license' => $this->faker->randomFloat(0, 1, 3),
            'pregnant_applicable' => $this->faker->randomFloat(0, 1, 3),
            'health_condition_id' => HealthCondition::factory(),
        ];
    }
}
