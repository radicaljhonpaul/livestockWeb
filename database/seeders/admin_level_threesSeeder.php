<?php

namespace Database\Seeders;

use App\Models\AdminLevelThree;
use Illuminate\Database\Seeder;

class admin_level_threesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminLevelThree::factory()->count(3)->create();
    }
}
