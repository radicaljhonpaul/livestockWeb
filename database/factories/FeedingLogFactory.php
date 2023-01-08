<?php

namespace Database\Factories;

use App\Models\Livestock;
use App\Models\FeedingLog;
use App\Models\SrpYear;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedingLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\FeedingLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            'external_id' => $this->faker->uuid(),
            'visit_date' => $this->faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
            'body_weight' => $this->faker->randomFloat(2, 0, 500),
            'category' => $this->faker->randomElement(['CA', 'CO', 'HE']),
            'is_lactating' => $this->faker->boolean($chanceOfGettingTrue = 50),
            'lactation_stage' => $this->faker->randomElement(['EA', 'MI', 'LA']),
            'is_pregnant' => $this->faker->boolean($chanceOfGettingTrue = 50),
            'ave_daily_gain' => $this->faker->randomFloat(2, 0, 1),
            'milk_yield_per_day' => $this->faker->randomFloat(2, 0, 8),
            'milk_price' => $this->faker->randomFloat(2, 50, 100),
            'total_cost_per_day' => $this->faker->randomFloat(2, 80, 300),
            'feed_cost_per_kg' => $this->faker->randomFloat(2, 80, 300),
            'income' => $this->faker->randomFloat(2, 300, 400),
            'ration_name' => $this->faker->company(),
            'pregnancy_month' => '8',
            'fat_protein' => $this->faker->randomElement(['4', '5', '6']),

            'srp_year_id' => SrpYear::factory(),
            'livestock_id' => Livestock::factory(),
            'created_by' => User::factory(),


        ];
    }
}
