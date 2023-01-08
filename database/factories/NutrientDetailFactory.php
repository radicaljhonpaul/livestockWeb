<?php

namespace Database\Factories;

use App\Models\NutrientDetail;
use App\Models\FeedingLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class NutrientDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NutrientDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['RE', 'RA']),
            'dm' => $this->faker->randomFloat(2, 0, 100),
            'me' => $this->faker->randomFloat(2, 0, 100),
            'cp' => $this->faker->randomFloat(2, 0, 100),
            'ndf' => $this->faker->randomFloat(2, 0, 100),
            'tdn' => $this->faker->randomFloat(2, 0, 100),
            'ca' => $this->faker->randomFloat(2, 0, 100),
            'p' => $this->faker->randomFloat(2, 0, 100),
            'feeding_log_id' => FeedingLog::factory(),
        ];
    }
}
