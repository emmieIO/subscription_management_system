<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Faker\Factory as Faker;


class RegisterTest extends TestCase{
    use RefreshDatabase;
    public function test_user_can_register(){
        $this->artisan('db:seed');
        $faker = Faker::create();
        $response = $this->postJson('api/register', [
            "firstname" => $faker->firstName,
            "lastname" => $faker->lastName,
            "email" => $faker->safeEmail(),
            "password" => "Password123",
            "password_confirmation" => "Password123",
        ]);
        $response->assertStatus(201);
    }

    public function test_user_cannot_register_with_empty_data(){
        $response = $this->postJson('api/register', []);
        $response->assertStatus(422);
    }

    public function test_user_cannot_register_with_invalid_data(){
        $response = $this->postJson('api/register', [
            "firstname" => "",
            "lastname" => "",
            "email" => "",
            "password" => "Password123",
            "password_confirmation" => "Password123",
        ]);
        $response->assertStatus(422);
    }

    public function test_user_already_exist(){
        $this->artisan('db:seed');
        $faker = Faker::create();
        $response = $this->postJson('api/register', [
            "firstname" => $faker->firstName,
            "lastname" => $faker->lastName,
            "email" => "admin@subscriber.com",
            "password" => "Password123",
            "password_confirmation" => "Password123",
        ]);
        $response->assertStatus(422);
    }
}
