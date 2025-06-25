<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class MenuItemsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    /** @test */
    public function  He_has_to_register_item_in_the_database()
    {
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
        $this->withoutExceptionHandling();
        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'admin',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);

        $data = [
            "name" => "skol",
            "description" => "é uma cerveja clara, com aroma refinado e sabor leve e suave",
            "price" => "5,20",
            "category" => "cerveja",
            "stock_quantity" => "2",
            "is_available" => true
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('api/items/register', $data);

        $response->assertStatus(201);
        $response->assertJson(["msg" => "Items registered successfully"]);
    }
}
