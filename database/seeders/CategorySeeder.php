<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'otong123')->first();

        Category::create([
            'name' => 'Horror',
            'description' => 'Horror Description',
            'user_id' => $user->id
        ]);

        Category::create([
            'name' => 'Technology',
            'description' => 'Technology Description',
            'user_id' => $user->id
        ]);
    }
}
