<?php

namespace Database\Factories;

use App\Models\HealthCondition;
use App\Models\MediaHealthCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaHealthConditionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaHealthCondition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'health_condition_id' => HealthCondition::factory(),
            'path_name' => $this->faker->name(),
            'type' => 'jpg',
        ];
    }
}
