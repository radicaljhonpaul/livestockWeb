<?php

namespace Database\Seeders;

use App\Models\Livestock;
use Illuminate\Database\Seeder;

class livestocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Livestock::factory()->count(36)->create();
    }
}
