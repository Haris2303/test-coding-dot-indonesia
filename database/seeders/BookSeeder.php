<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'otong123')->first();
        $category = Category::where('name', 'Horror')->first();

        Book::create([
            'title' => 'Kau Pahlawanku',
            'trailer' => 'adsf adsf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf sdaf',
            'publication_year' => '2023',
            'quantity' => 5,
            'author' => 'ucup',
            'publisher' => 'otong',
            'shell_code' => 'RK01',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        Book::create([
            'title' => 'Dia Pahlawanku',
            'trailer' => 'adsf adsf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf sdaf',
            'publication_year' => '2023',
            'quantity' => 5,
            'author' => 'ucup',
            'publisher' => 'otong',
            'shell_code' => 'RK02',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
    }
}
