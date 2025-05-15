<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private array $userData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userData = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $this->user = User::factory()->create([
            'email' => $this->userData['email'],
            'password' => bcrypt($this->userData['password'])
        ]);
    }

    public function test_user_can_register_successfully()
    {
        $newUserData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $newUserData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User'
        ]);
    }

    public function test_user_can_login_successfully()
    {
        $response = $this->postJson('/api/login', $this->userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $loginData = [
            'email' => $this->userData['email'],
            'password' => 'wrong_password'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Credenciais inválidas'
            ]);
    }

    public function test_user_can_logout_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout realizado com sucesso'
            ]);
    }

    public function test_registration_fails_with_invalid_data()
    {
        $userData = [
            'name' => '',
            'email' => 'invalid_email',
            'password' => '123',
            'password_confirmation' => '123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
