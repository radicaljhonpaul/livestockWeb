<?php

namespace Database\Seeders;

use App\Models\AdminLevelOne;
use Illuminate\Database\Seeder;

class admin_level_onesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminLevelOne::factory()->count(3)->create();
    }
}
