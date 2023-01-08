<?php

namespace Database\Factories;

use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogSymptom;
use App\Models\Symptom;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnosisLogSymptomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiagnosisLogSymptom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'diagnosis_log_id' => DiagnosisLog::factory(),
            'symptom_id' => Symptom::factory(),
        ];
    }
}
