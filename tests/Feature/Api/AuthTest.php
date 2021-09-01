<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validations_auth()
    {
        $response = $this->postJson('/auth', []);

        $response->assertStatus(422);
    }

    public function test_auth_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com'
        ]);

        $response = $this->postJson('/auth', [
            'email' => 'test@test.com',
            'password' => 'wrong_password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(422);
    }

    public function test_auth()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com'
        ]);

        $response = $this->postJson('/auth', [
            'email' => 'test@test.com',
            'password' => 'password',
            'device_name' => 'test',
        ]);

        $response->assertStatus(200)
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

    public function test_logout_unauthenticated()
    {
        $response = $this->postJson('/logout', []);

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->postJson('/logout', []);

        $response->assertStatus(200);
    }

    public function test_me_unauthenticated()
    {
        $response = $this->getJson('/me', []);

        $response->assertStatus(401);
    }

    public function test_me()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/me');

        $response->assertStatus(200);
    }
}
