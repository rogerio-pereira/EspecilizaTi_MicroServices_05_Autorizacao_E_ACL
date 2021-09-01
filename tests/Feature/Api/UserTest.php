<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_users_unauthenticated()
    {
        $response = $this->getJson('/users');

        $response->assertStatus(401);
    }

    public function test_get_users_unauthoried()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/users');

        $response->assertStatus(403);
    }

    public function test_get_users()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/users');

        $response->assertStatus(200);
    }

    public function test_count_users()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $users = User::factory()->count(10)->create();
        $user = $users[0];
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/users');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    public function test_get_single_user_fail()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/users/fake');

        $response->assertStatus(404);
    }

    public function test_get_single_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->getJson('/users/'.$user->uuid);

        // $response->dump();
        $response->assertStatus(200);
    }
}
