<?php

namespace Database\Factories;

use App\Models\DiagnosisLog;
use App\Models\DiagnosisLogHealthCondition;
use App\Models\HealthCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnosisLogHCFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiagnosisLogHealthCondition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => DiagnosisLog::factory(),
            'diagnosis_log_id' => DiagnosisLog::factory(),
            'health_condition_id' => HealthCondition::factory(),
        ];
    }
}
