<?php

namespace Database\Factories;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\DiagnosisLog;
use App\Models\HealthCondition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class DiagnosisLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\DiagnosisLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [

            'visit_date' => $this->faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
            'activity_type' => 'DV',
            'status' => 'ON',
            // 'assessment'=> 'CR',
            'assessment'=> $this->faker->randomElement(['CR', 'IM']),
            'notes'=>  $this->faker->text($maxNbChars = 1000),
            'created_by' => User::factory(),
            'authorized_by' => User::factory(),
            'assigned_to' => User::factory(),
            'livestock_id' => Livestock::factory(),
            'external_id' => $this->faker->uuid()
             //delete health_condition_id from factory as it is established that 1 diagnosis log can have multiple HCs (moved to separate table) 

        ];
    }
}
