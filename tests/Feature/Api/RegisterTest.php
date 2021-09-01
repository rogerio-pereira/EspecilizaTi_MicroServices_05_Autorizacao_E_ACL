<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validation_register()
    {
        $response = $this->postJson('/register', []);

        $response->assertStatus(422);
    }

    public function test_register()
    {
        $response = $this->postJson('/register', [
            'name' => 'User Test',
            'email' => 'test@test.com',
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'identify',
                    'name',
                    'email',
                    'permissions' => []
                ],
                'token'
            ]);
    }
}
