<?php

namespace Database\Seeders;

use App\Models\AdminLevelTwo;
use Illuminate\Database\Seeder;

class admin_level_twosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminLevelTwo::factory()->count(3)->create();
    }
}
