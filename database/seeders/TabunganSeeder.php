<?php

namespace Database\Seeders;

use App\Models\Tabungan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TabunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Tabungan::factory()->count(100)->create([
            'nominal' => rand(1000, 100000), // Nilai nominal diisi secara acak
        ]);
    }
}
