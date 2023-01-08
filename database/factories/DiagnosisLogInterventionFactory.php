<?php

namespace Database\Factories;

use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogIntervention;
use App\Models\HealthCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnosisLogInterventionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiagnosisLogIntervention::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'diagnosis_log_id' => DiagnosisLog::factory(),
            'intervention_id' => HealthCondition::factory(),
        ];
    }
}
