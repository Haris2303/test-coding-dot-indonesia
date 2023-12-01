<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/categories', headers: [
            'Authorization' => 'otong123'
        ], data: [
            'name' => 'Horror',
            'description' => 'Horror Description'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'Horror',
                'description' => 'Horror Description'
            ]
        ]);
    }

    public function testCreateFailed()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/categories', headers: [
            'Authorization' => 'otong123'
        ], data: [
            'name' => '',
            'description' => 'Horror Description'
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'name' => [
                    'The name field is required.'
                ]
            ]
        ]);
    }

    public function testGetAllSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::where('name', 'Horror');

        $this->get('/api/categories', [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'id' => $category->id,
                    'name' => 'Horror'
                ],
                [
                    'name' => 'Technology'
                ]
            ]
        ]);
    }

    public function testGetAllUnauthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $this->get('/api/categories')->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $oldCategory = Category::where('name', 'Horror')->first();
        $this->put('/api/category/' . $oldCategory->id, headers: [
            'Authorization' => 'otong123'
        ], data: [
            'name' => 'Petualangan'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Petualangan'
            ]
        ]);

        $newCategory = Category::where('name', 'Petualangan')->first();
        $this->assertNotEquals($oldCategory, $newCategory);
    }

    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $this->put('/api/category/' . 1, headers: [
            'Authorization' => 'otong123'
        ], data: [
            'name' => 'Petualangan'
        ])->assertStatus(404)->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::where('name', 'Horror')->first();
        $this->get('/api/category/' . $category->id, [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'Horror'
            ]
        ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::where('name', 'Horror')->first();
        $this->get('/api/category/' . $category->id, [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testGetFailedNotFound()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $this->get('/api/category/' . 1, [
            'Authorization' => 'otong123'
        ])->assertStatus(404)->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::where('name', 'Horror')->first();
        $this->delete('/api/category/' . $category->id, headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);
    }

    public function testDeleteFailedNotFound()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $this->delete('/api/category/' . 1, headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(404)->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }

    public function testDeleteFailedUnauthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::where('name', 'Horror')->first();
        $this->delete('/api/category/' . $category->id, headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }
}
