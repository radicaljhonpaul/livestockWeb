<?php

namespace Database\Seeders;

use App\Models\AdminLevelThree;
use App\Models\Farmer;
use Illuminate\Database\Seeder;

class farmerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Farmer::factory()->count(3)->create();
    }
}
