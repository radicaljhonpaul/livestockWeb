<?php

namespace Database\Factories;

use App\Models\HcSymptom;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Factories\Factory;

class SymptomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Symptom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_symptom' => 1,
            'name' => $this->faker->name(),
            'local_term' => $this->faker->name(),
        ];
    }
}
