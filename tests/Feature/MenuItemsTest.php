<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $data = [
            "name" => "skol",
            "description" => "é uma cerveja clara, com aroma refinado e sabor leve e suave",
            "price" => "5,20",
            "category" => "cerveja",
            "stock_quantity" => "2",
            "is_available" => true
        ];
        $response = $this->post('api/items/register', $data);
        $response->assertStatus(201);
        $response->assertJson(["msg" => "Items registered successfully"]);
    }
}
