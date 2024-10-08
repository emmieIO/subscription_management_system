<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $this->artisan('db:seed');
        $response = $this->postJson('api/login', [
            "email" => "test@subscriber.com",
            "password" => "password123@",
        ]);
        $response->assertStatus(200);
    }

    public function test_user_cannot_login_with_empty_data()
    {
        $response = $this->postJson('api/login', []);
        $response->assertStatus(422);
    }

    public function test_user_cannot_login_with_invalid_data()
    {
        $faker = Faker::create();
        $response = $this->postJson('api/login', [
            "email" => $faker->safeEmail,
            "password" => $faker->password,
        ]);
        $response->assertStatus(401);
    }
}