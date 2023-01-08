<?php

namespace Database\Factories;

use App\Models\HCSymptom;
use App\Models\HealthCondition;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Factories\Factory;

class HCSymptomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HCSymptom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'health_condition_id' => $this->faker->randomFloat(0, 1, 3),
            'health_condition_id' => HealthCondition::factory(),
            // 'symptom_id' => $this->faker->randomFloat(0, 1, 3),
            'symptom_id' => Symptom::factory(),
            'differential' => $this->faker->randomFloat(0, 0, 10),
        ];
    }
}
