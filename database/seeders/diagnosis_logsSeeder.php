<?php

namespace Database\Seeders;

use App\Models\DiagnosisLog;
use Illuminate\Database\Seeder;

class diagnosis_logsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiagnosisLog::factory()->create();
    }
}
