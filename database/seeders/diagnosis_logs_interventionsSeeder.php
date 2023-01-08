<?php

namespace Database\Seeders;

use App\Models\DiagnosisLogIntervention;
use Illuminate\Database\Seeder;

class diagnosis_logs_interventionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiagnosisLogIntervention::factory()->count(36)->create();
    }
}
