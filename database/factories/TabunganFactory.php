<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tabungan>
 */
class TabunganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ms_siswa_id' => $this->faker->numberBetween(1, 50),
            'jenis_transaksi' => $this->faker->randomElement(['setoran', 'penarikan']),
            'nominal' => $this->faker->numberBetween(1000, 100000), // Pastikan nilai nominal diisi
            'saldo_akhir' => 0, // Default saldo akhir
            'ms_pengguna_id' => 1
            // 'ms_pengguna_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
