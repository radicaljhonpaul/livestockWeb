<?php

namespace Database\Factories;

use App\Models\OrganSystem;
use App\Models\OrganSystemSymptom;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganSystemSymptomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganSystemSymptom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organ_system_id' => OrganSystem::factory(),
            'symptom_id' => Symptom::factory(),
        ];
    }
}
