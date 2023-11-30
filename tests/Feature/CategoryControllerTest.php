<?php

namespace Tests\Feature;

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

        $this->get('/api/categories', [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
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
}
