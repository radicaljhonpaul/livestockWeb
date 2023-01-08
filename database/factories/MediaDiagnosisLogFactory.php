<?php

namespace Database\Factories;

use App\Models\DiagnosisLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class MediaDiagnosisLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\MediaDiagnosisLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [

            'path_name' => $this->faker->imageUrl($width = 640, $height = 480),
            'type' => $this->faker->fileExtension(),
            'filesize' => 3730437,
            'diagnosis_log_id' => DiagnosisLog::factory(),

        ];
    }
}
