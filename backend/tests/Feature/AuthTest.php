<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Attempt login
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user',
                    'token'
                ]
            ])
            ->assertJson([
                'data' => [
                    'user' => [
                        'email' => 'test@example.com'
                    ]
                ]
            ]);
    }

    /**
     * Test user login with invalid credentials
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    /**
     * Test user can get their profile when authenticated
     */
    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user'
                ]
            ])
            ->assertJson([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email
                    ]
                ]
            ]);
    }

    /**
     * Test unauthenticated user cannot get profile
     */
    public function test_unauthenticated_user_cannot_get_profile(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully'
            ]);

        // Verify token is revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token'
        ]);
    }

    /**
     * Test token refresh
     */
    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }
}
