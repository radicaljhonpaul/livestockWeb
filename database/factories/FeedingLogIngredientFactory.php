<?php

namespace Database\Factories;

use App\Models\Livestock;
use App\Models\FeedingLog;
use App\Models\FeedingLogIngredient;
use App\Models\Ingredient;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedingLogIngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\FeedingLogIngredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            'quantity' => $this->faker->randomFloat(2, 50, 100),
            'price_at_date' => $this->faker->randomFloat(2, 80, 300),

            'feeding_log_id' => FeedingLog::factory(),
            'ingredient_id' => Ingredient::factory(),


        ];
    }
}
