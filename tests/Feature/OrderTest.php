<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu_Item;
use App\Models\Orders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function he_must_place_an_order_successfully()
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
            "price" => "5.20",
            "category" => "cerveja",
            "stock_quantity" => 2,
            "is_available" => true
        ]);

        $user_id = User::where("email", "Carlos@email.com")->first()->id;
        $item_id = Menu_Item::where("name", "skol")->first()->item_id;

        $data = [
            "user_id" => $user_id,
            "item_id" => $item_id,
            "status" => "process",
            "quantity" => 1
        ];

        $response = $this->withHeaders(headers: [
            "Authorization" => "Bearer $token"
        ])->postJson("api/orders", $data);

        $response->assertStatus(200);
        $response->assertJson(["msg" => "Successful registered order"]);
    }
    /** @test */
    public function Must_update_the_order()
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
            "price" => "5.20",
            "category" => "cerveja",
            "stock_quantity" => 2,
            "is_available" => true
        ]);

        $user_id = User::where("email", "Carlos@email.com")->first()->id;
        $item_id = Menu_Item::where("name", "skol")->first()->item_id;

        Orders::create([
            "user_id" => $user_id,
            "item_id" => $item_id,
            "status" => "process",
            "order_price" => 15.60,
            "quantity" => 3
        ]);
        $order_id = Orders::where([
            'user_id' => $user_id,
            'item_id' => $item_id
        ])->value("order_id");

        $data = [
            /* "type" => "status",
            "status"=> "pending_payment",*/
            "item_id" => $item_id,
            "order_id" => $order_id,
            "quantity" => 1
        ];

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token"
        ])->putJson("api/orders/{$order_id}", $data);
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Successful update order"]);
    }
    /** @test */
    public function he_must_read_the_database_orders()
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
            "price" => "5.20",
            "category" => "cerveja",
            "stock_quantity" => 2,
            "is_available" => true
        ]);

        $user_id = User::where("email", "Carlos@email.com")->first()->id;
        $item_id = Menu_Item::where("name", "skol")->first()->item_id;

        Orders::create([
            "user_id" => $user_id,
            "item_id" => $item_id,
            "status" => "process",
            "order_price" => 15.60,
            "quantity" => 3
        ]);
        $response = $this->withHeaders([
            "Authorization" => "Bearer $token"
        ])->getJson("api/orders?user_id=$user_id");

        $response->assertStatus(200);
        $response->assertJson(["msg" => "Successful get order"]);
    }
    /** @test */
    public function he_must_delete_the_orders_in_database()
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
            "price" => "5.20",
            "category" => "cerveja",
            "stock_quantity" => 2,
            "is_available" => true
        ]);

        $user_id = User::where("email", "Carlos@email.com")->first()->id;
        $item_id = Menu_Item::where("name", "skol")->first()->item_id;

        Orders::create([
            "user_id" => $user_id,
            "item_id" => $item_id,
            "status" => "process",
            "order_price" => 15.60,
            "quantity" => 3
        ]);

        $order_id = Orders::where([
            "user_id" => $user_id,
            "item_id" => $item_id
        ])->first()->order_id;

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token"
        ])->DeleteJson("api/orders/{$order_id}");
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Order deleted from database"]);
    }
    /** @test */
    public function should_send_a_message_to_the_customer()
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
            "price" => "5.20",
            "category" => "cerveja",
            "stock_quantity" => 2,
            "is_available" => true
        ]);

        $user_id = User::where("email", "Carlos@email.com")->first()->id;
        $item_id = Menu_Item::where("name", "skol")->first()->item_id;

        Orders::create([
            "user_id" => $user_id,
            "item_id" => $item_id,
            "status" => "process",
            "order_price" => 15.60,
            "quantity" => 3
        ]);

        $data = [
            "user_id" => $user_id,
        ];
        $response = $this->withHeaders([
            "Authorization" => "Bearer $token"
        ])->postJson("api/orders/customer",$data);
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Payment sent to the customer"]);
    }
}
