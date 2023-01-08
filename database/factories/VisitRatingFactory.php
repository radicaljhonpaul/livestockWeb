<?php

namespace Database\Factories;

use App\Models\VisitRating;
use App\Models\Farmer;
use App\Models\Livestock;
use App\Models\VisitLog;
use App\Models\DiagnosisLog;
use App\Models\FeedingLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\VisitRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            'rating' => 'Mabilis',
            'message' => 'Sample Message',
            'type' => 'Sample Type',
            'farmer_id' => Farmer::factory(),
            'livestock_id' => Livestock::factory(),
            'assigned_to' => User::factory(),

            'diagnosis_log_id' => DiagnosisLog::factory(),
            'feeding_log_id' => FeedingLog::factory(),
        ];
    }
}
