<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function He_has_to_register_the_user_in_the_database(){
        $this->withoutExceptionHandling();
        $data = [
            'name'=>'Carlos Souza',
            'email'=> 'Carlos@email.com',
            'password'=>'1234',
        ];
        $response = $this->post('/users/register',$data);
        $response->assertStatus(200);
        $response->assertJson(['msg'=>'User registered successfully']);


    }
}
