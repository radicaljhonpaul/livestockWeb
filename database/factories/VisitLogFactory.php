<?php

namespace Database\Factories;

use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\VisitLog;
use App\Models\DiagnosisLog;
use App\Models\FeedingLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class VisitLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\VisitLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [

            'visit_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'farmer_id' => Farmer::factory(),
            'livestock_id' => Livestock::factory(),
            'assigned_to' => User::factory(),

            'diagnosis_log_id' => DiagnosisLog::factory(),
            'feeding_log_id' => FeedingLog::factory(),

            

        ];
    }
}
