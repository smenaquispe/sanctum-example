<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class SanctumTest extends TestCase
{
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'sergioz@gmail.com',
            'password' => '123456',
        ]);
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'name',
                'email',
            ],
            'token',
        ]);
    }

    public function test_user_can_see_route(): void
    {
        $user = User::factory()->create([
            'email' => 'sergiozasdswddsas@gmail.com',
            'password' => '123456',
        ]);
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $token = $response->json('token');

        $response = $this->get('/api/user', [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'name',
            'email',
        ]);
        $response->assertJson([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_user_can_request_with_ability(): void
    {
        $user = User::factory()->create([
            'email' => 'sergiozasdswsdaddsas@gmail.com',
            'password' => '123456',
        ]);
        
        Sanctum::actingAs($user, ['create-post']);

        $response = $this->get('/api/post/create', [
            'title' => 'Test post',
            'body' => 'Test body',
        ]); 

        $response->assertStatus(200);
    }
}
