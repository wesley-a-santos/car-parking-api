<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUserCanRegisterWithCorrectCredentials()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'access_token',
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function testUserCannotRegisterWithIncorrectCredentials()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'John Does',
            'email'                 => 'johns@example.com',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseMissing('users', [
            'name'  => 'John Does',
            'email' => 'johns@example.com',
        ]);
    }
}
