<?php

namespace Database\Seeders;

use App\Models\Raperda;
use Illuminate\Database\Seeder;

class RaperdaSeeder extends Seeder
{
    public function run(): void
    {
        // bikin 30 data dummy
        Raperda::factory()->count(30)->create();
    }
}
