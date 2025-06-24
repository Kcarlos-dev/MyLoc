<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function He_has_to_register_the_user_in_the_database()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => 'Carlos Souza',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => '1234',
        ];
        $response = $this->post('api/users/register', $data);
        $response->assertStatus(201);
        $response->assertJson(['msg' => 'User registered successfully']);

        $this->assertDatabaseHas('users', [
            'name' => 'Carlos Souza',
            'email' => 'Carlos@email.com',
        ]);
    }
    /** @test */
    public function it_should_return_the_user_login()
    {
        $this->withoutExceptionHandling();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);

        User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'User',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);
        $data = [
            'email' => 'Carlos@email.com',
            'password' => '1234'
        ];
        $response = $this->post('api/users/login', $data);
        $response->assertStatus(200);
        $response->assertJson(['msg' => 'User return successfully']);
    }

    /** @test */
    public function it_returns_the_authenticated_user()
    {
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);

        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'User',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/users/me');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }
    /** @test */
    public function He_invalidates_JWT()
    {
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);

        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'User',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/users/exit');
        $response->assertStatus(200);
        $response->assertJson(['msg' => 'User exit']);
    }
}
