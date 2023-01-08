<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'dm' => $this->faker->randomFloat(2, 0, 100),
            'me' => $this->faker->randomFloat(2, 0, 100),
            'cp' => $this->faker->randomFloat(2, 0, 100),
            'ndf' => $this->faker->randomFloat(2, 0, 100),
            'tdn' => $this->faker->randomFloat(2, 0, 100),
            'min' => $this->faker->randomFloat(2, 0, 100),
            'max' => $this->faker->randomFloat(2, 0, 100),
            'ca' => $this->faker->randomFloat(2, 0, 100),
            'p' => $this->faker->randomFloat(2, 0, 100),
            'min' => '1',
            'max' => '3',
            'preload' => '1',
            'category_id' => Category::factory(),
        ];
    }
}
