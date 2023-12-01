<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Database\Seeders\BookSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::all()->first();

        $this->post("/api/category/$category->id/books", headers: [
            'Authorization' => 'otong123'
        ], data: [
            'title' => 'asd',
            'trailer' => 'asd',
            'publication_year' => '2023',
            'quantity' => 5,
            'author' => 'otong',
            'publisher' => 'ucup',
            'shell_code' => 'RK23',
        ])->assertStatus(201)->assertJson([
            'data' => [
                'title' => 'asd',
                'trailer' => 'asd',
                'publication_year' => '2023',
                'quantity' => 5,
                'author' => 'otong',
                'publisher' => 'ucup',
                'shell_code' => 'RK23'
            ]
        ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class]);

        $category = Category::all()->first();

        $this->post("/api/category/$category->id/books", headers: [
            'Authorization' => 'otong123'
        ], data: [
            'title' => '',
            'trailer' => '',
            'publication_year' => '2023',
            'quantity' => 5,
            'author' => 'otong',
            'publisher' => 'ucup',
            'shell_code' => 'RK23',
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'title' => [
                    'The title field is required.'
                ],
                'trailer' => [
                    'The trailer field is required.'
                ]
            ]
        ]);
    }

    public function testSearchByTitle()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?title=Kau%20Pahlawanku", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ]
            ]
        ]);
    }

    public function testSearchByPublicationYear()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?publication_year=2023", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ]
            ]
        ]);
    }

    public function testSearchByAuthor()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?author=ucup", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ]
            ]
        ]);
    }

    public function testSearchByPublisher()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?publisher=otong", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ]
            ]
        ]);
    }

    public function testSearchByShellCode()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?shell_code=RK01", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ]
            ]
        ]);
    }

    public function testSearchFailedUnAuthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $this->get("/api/books?shell_code=RK01", headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testGetByCategorySuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $category = Category::where('name', 'Horror')->first();

        $this->get("/api/category/$category->id/books", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                [
                    'title' => 'Kau Pahlawanku'
                ],
                [
                    'title' => 'Dia Pahlawanku'
                ]
            ]
        ]);
    }

    public function testGetByCategoryFailedUnauthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $category = Category::where('name', 'Horror')->first();

        $this->get("/api/category/$category->id/books", headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->put("/api/category/$book->category_id/book/$book->id", headers: [
            'Authorization' => 'otong123'
        ], data: [
            'title' => 'Saya Adalah Pahlawan',
            'trailer' => 'Trailer Buku',
            'publication_year' => '2023',
            'quantity' => 3,
            'author' => 'Otong',
            'publisher' => 'Ucup',
            'shell_code' => 'RK23'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'title' => 'Saya Adalah Pahlawan'
            ]
        ]);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->put("/api/category/$book->category_id/book/$book->id", headers: [
            'Authorization' => 'otong123'
        ], data: [
            'title' => 'asdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaas
                        dfafasdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaas
                        dfafasdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaasdfafas
                        dfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadffafdaasdfafasdfasdfsdafadsfadff
                        afdaasdfafasdfasdfsdafadsfadffafdaasdfaf',
            'publication_year' => ''
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'title' => [
                    'The title field must not be greater than 255 characters.'
                ],
                'publication_year' => [
                    'The publication year field is required.'
                ]
            ]
        ]);
    }

    public function testGetByCategoryAndBookSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->get("/api/category/$book->category_id/book/$book->id", [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => [
                'title' => 'Kau Pahlawanku'
            ]
        ]);
    }

    public function testGetByCategoryAndBookFailedUnauthorized()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->get("/api/category/$book->category_id/book/$book->id", [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->delete("/api/category/$book->category_id/book/$book->id", headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);
    }

    public function testDeleteFailed()
    {
        $this->seed([UserSeeder::class, CategorySeeder::class, BookSeeder::class]);

        $book = Book::where('title', 'Kau Pahlawanku')->first();

        $this->delete("/api/category/$book->category_id/book/" . 1, headers: [
            'Authorization' => 'otong123'
        ])->assertStatus(404)->assertJson([
            'errors' => [
                'message' => [
                    'book not found'
                ]
            ]
        ]);
    }
}
