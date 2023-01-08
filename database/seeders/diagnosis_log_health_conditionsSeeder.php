<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiagnosisLogHealthCondition;

class diagnosis_log_health_conditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiagnosisLogHealthCondition::factory()->count(36)->create();
    }
}
