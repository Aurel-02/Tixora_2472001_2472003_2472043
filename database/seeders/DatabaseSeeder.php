<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        DB::table('admin')->updateOrInsert(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin',
                'password' => Hash::make('password'),
                'role' => '1',
            ]
        );

        // Organizer
        User::updateOrCreate(
            ['email' => 'organizer@gmail.com'],
            [
                'nama_lengkap' => 'Organizer',
                'password' => Hash::make('password'),
                'role' => '2',
                'no_telp' => '0000000001'
            ]
        );

        // Pembeli (Buyer)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'nama_lengkap' => 'Test User',
                'password' => Hash::make('password'),
                'role' => '3', // role 3 / buyer
                'no_telp' => '0000000002'
            ]
        );
    }
}
