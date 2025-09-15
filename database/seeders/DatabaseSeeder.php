<?php

namespace Database\Seeders;

use App\Models\Consumption;
use App\Models\MeetingRoom;
use App\Models\Unit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        Unit::create([
            'name' => 'Unit 1',
        ]);

        User::insert([
            [
                'name' => 'Super Admin',
                'email' => 'super@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
                'unit_id' => null,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'unit_id' => null,
            ],
            [
                'name' => 'Anggota',
                'email' => 'anggota@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'unit_id' => 1,
            ]
        ]);

        MeetingRoom::create([
            'name' => 'Room 1',
            'capacity' => 10
        ]);

        Consumption::insert([
            [
                'name' => 'Snack Siang',
                'price' => 20000,
            ],
            [
                'name' => 'Makan Siang',
                'price' => 30000,
            ],
            [
                'name' => 'Snack Sore',
                'price' => 20000,
            ],
        ]);
    }
}
