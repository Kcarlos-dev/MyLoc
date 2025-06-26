<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu_Item;
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
        $this->withoutExceptionHandling();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
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
    /** @test */
    public function he_must_read_the_bank_items()
    {
        $this->withoutExceptionHandling();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'admin',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);

        Menu_Item::create([
            "name" => "skol",
            "description" => "é uma cerveja clara, com aroma refinado e sabor leve e suave",
            "price" => "5,20",
            "category" => "cerveja",
            "stock_quantity" => "2",
            "is_available" => true
        ]);

        $name = "skol";

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("api/items?name=$name");

        $response->assertStatus(200);
        $response->assertJson(["msg" => "Items found successfully"]);
    }
    /** @test */
    public function he_must_make_the_item_update()
    {
        $this->withoutExceptionHandling();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'admin',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);

        Menu_Item::create([
            "name" => "skol",
            "description" => "é uma cerveja clara, com aroma refinado e sabor leve e suave",
            "price" => "5,20",
            "category" => "cerveja",
            "stock_quantity" => "2",
            "is_available" => true
        ]);
        $data = [
            "name" => "skol",
            "stock_quantity" => "3"
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson("api/items/changed", $data);
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Successfully changed product"]);
    }
    /** @test */
    public function he_must_delete_item_in_database()
    {
        $this->withoutExceptionHandling();
        $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
        $user = User::create([
            'name' => 'Carlos Souza',
            'user_type' => 'admin',
            'email' => 'Carlos@email.com',
            'phone' => '96 0000000',
            'password' => $passwordHash
        ]);

        $token = JWTAuth::fromUser($user);

        Menu_Item::create([
            "name" => "skol",
            "description" => "é uma cerveja clara, com aroma refinado e sabor leve e suave",
            "price" => "5,20",
            "category" => "cerveja",
            "stock_quantity" => "2",
            "is_available" => true
        ]);
        $name = "skol";
        $reponse = $this->withHeaders([
            "Authorization" => "Bearer $token"
        ])->deleteJson("api/items/{$name}");
        $reponse->assertStatus(200);
        $reponse->assertJson(["msg" => "Item deleted from database"]);
    }
}
