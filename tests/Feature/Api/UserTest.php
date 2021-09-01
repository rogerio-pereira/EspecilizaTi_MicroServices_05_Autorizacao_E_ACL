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

    public function test_validations_store_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->postJson('/users', []);

        $response->assertStatus(422);
    }

    public function test_store_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->postJson('/users', [
                            'name' => 'Test User',
                            'email' => 'test@test.com',
                            'password' => 'password',
                        ]);

        $response->assertStatus(201);
    }

    public function test_update_user_404()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->putJson('/users/fake', [
                            'name' => 'Test User',
                            'email' => 'test@test.com',
                            'password' => 'password',
                        ]);

        $response->assertStatus(404);
    }

    public function test_validations_update_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->putJson('/users/'.$user->uuid, []);

        $response->assertStatus(422);
    }

    public function test_update_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->putJson('/users/'.$user->uuid, [
                            'name' => 'Test User Updated',
                            'email' => 'test@test.com',
                        ]);

        $response->assertStatus(200);
    }

    public function test_delete_user_404()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->deleteJson('/users/fake');

        $response->assertStatus(404);
    }

    public function test_delete_user()
    {
        $permission = Permission::factory()->create(['name' => 'users']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
                        ->deleteJson('/users/'.$user->uuid);

        $response->assertStatus(200);
    }
}
