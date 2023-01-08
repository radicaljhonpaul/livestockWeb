<?php

namespace Database\Factories;

use App\Models\MediaSymptom;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaSymptomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaSymptom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'symptom_id' => Symptom::factory(),
            'path_name' => $this->faker->name(),
            'type' => 'jpg',
        ];
    }
}
