<?php

namespace Database\Factories;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\AdminLevelThree;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class LivestockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Livestock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'carabao_code' => $this->faker->uuid(),
            // 'carabao_code' => $this->faker->unique()->randomDigitNotNull,
            // 'breed' => $this->faker->colorName(),
            'breed' => 'bk',
            'sex' => 'f',
            'year_of_birth' => $this->faker->year(),
            'registration_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'is_pregnant' => $this->faker->boolean($chanceOfGettingTrue = 50),
            'farmer_id' => Farmer::factory(),

        ];
    }
}
