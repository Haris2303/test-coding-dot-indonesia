<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'name' => 'Otong Marotong',
            'username' => 'otong123',
            'password' => 'otong123'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'Otong Marotong',
                'username' => 'otong123'
            ]
        ]);
    }
}
