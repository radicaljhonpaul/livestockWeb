<?php

namespace Database\Seeders;

use App\Models\MediaDiagnosisLog;
use Illuminate\Database\Seeder;

class media_diagnosis_logsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MediaDiagnosisLog::factory()->count(2)->create();
    }
}
