<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    use RefreshDatabase;

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('testpassword'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'testuser@example.com',
            'password' => 'testpassword',
        ],['Accept' => 'application/json']);
        // dd($response->json());
        dd($response->status(), $response->json());


        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    // public function test_login_invalid_credentials()
    // {
    //     User::factory()->create([
    //         'email' => 'testuser@example.com',
    //         'password' => bcrypt('testpassword'),
    //     ]);

    //     $response = $this->postJson('/login', [
    //         'email' => 'testuser@example.com',
    //         'password' => 'testpassword',
    //     ]);

    //     $response->assertStatus(401)
    //              ->assertJsonMissing(['token']);
    // }
}
