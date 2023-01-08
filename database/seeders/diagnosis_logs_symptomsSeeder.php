<?php

namespace Database\Seeders;

use App\Models\DiagnosisLogSymptom;
use Illuminate\Database\Seeder;

class diagnosis_logs_symptomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiagnosisLogSymptom::factory()->count(36)->create();
    }
}
