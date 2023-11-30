<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Otong Marotong',
            'username' => 'otong123',
            'password' => Hash::make('otong123'),
            'token' => 'otong123'
        ]);

        User::create([
            'name' => 'Ucup Marucup',
            'username' => 'ucup123',
            'password' => Hash::make('ucup123'),
            'token' => 'ucup123'
        ]);
    }
}
