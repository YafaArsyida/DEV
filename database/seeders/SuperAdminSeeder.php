<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'yafaarsyida23@gmail.com',
            ],
            [
                'nama' => 'Yafa Super',
                'password' => Hash::make('yAf4:)1815'),
                'telepon' => '08889727492',
                'peran' => 'SUPERADMIN',
                'current_session' => null,
            ]
        );
    }
}
