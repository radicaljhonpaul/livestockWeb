<?php

namespace Database\Factories;

use App\Models\HealthCondition;
use App\Models\OrganSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthConditionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HealthCondition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'local_term' => $this->faker->name(),
            'common_in_region' => $this->faker->name(),
            'description' => $this->faker->name(),
            'how_to_diganose' => $this->faker->name(),
            'treatment' => $this->faker->name(),
            'advice_to_farmer' => $this->faker->name(),
            'preventive_measure' => $this->faker->name(),
            'quick_action'=> 1,
            'zoonotic' => $this->faker->randomElement([0,1]),
            'organ_system_id' => OrganSystem::factory(),
        ];
    }
}
