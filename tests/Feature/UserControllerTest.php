<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private $name = 'Otong Marotong';
    private $user = 'otong123';

    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'name' => $this->name,
            'username' => $this->user,
            'password' => $this->user
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => $this->name,
                'username' => $this->user
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'name' => $this->name,
            'username' => '',
            'password' => ''
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'username' => [
                    'The username field is required.'
                ],
                'password' => [
                    'The password field is required.'
                ]
            ]
        ]);
    }

    public function testRegisterAlreadyExists()
    {
        $this->testRegisterSuccess();

        $this->post('/api/users', [
            'name' => $this->name,
            'username' => $this->user,
            'password' => $this->user
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'username' => [
                    'Username already registered'
                ]
            ]
        ]);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            'username' => $this->user,
            'password' => $this->user
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => $this->user,
            ]
        ]);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            'username' => '123445626',
            'password' => $this->user
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'username or password wrong'
                ]
            ]
        ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            'username' => $this->user,
            'password' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'username or password wrong'
                ]
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            'Authorization' => $this->user
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => $this->user
            ]
        ]);
    }

    public function testGetUnauthorization()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current')->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('username', $this->user)->first();

        $this->patch('/api/users/current', [
            'name' => 'New Name'
        ], [
            'Authorization' => $this->user
        ])->assertStatus(200)->assertJson([
            'data' => [
                'name' => 'New Name'
            ]
        ]);

        $newUser = User::where('username', $this->user)->first();
        $this->assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('username', $this->user)->first();

        $this->patch('/api/users/current', [
            'password' => 'New Password'
        ], [
            'Authorization' => $this->user
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => $this->user,
                'name' => 'Otong Marotong'
            ]
        ]);

        $newUser = User::where('username', $this->user)->first();
        $this->assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateFailed()
    {
        $this->seed(UserSeeder::class);

        $this->patch('/api/users/current', [
            'name' => 'blablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablablabla'
        ], [
            'Authorization' => $this->user
        ])->assertStatus(400)->assertJson([
            'errors' => [
                'name' => [
                    'The name field must not be greater than 100 characters.'
                ]
            ]
        ]);
    }
    public function testLogoutSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->delete('/api/users/logout', headers: [
            'Authorization' => $this->user
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);

        $user = User::where('username', $this->user)->first();
        $this->assertNull($user->token);
    }
}
