<?php

namespace Database\Seeders;

use App\Models\VisitLog;
use Illuminate\Database\Seeder;

class visit_logsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VisitLog::factory()->count(3)->create();
    }
}
