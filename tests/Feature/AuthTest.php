<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_book()
    {
        $payload = [
            'title' => 'Unauthorized Book',
            'author' => 'Unknown',
            'isbn' => 'ISBN-UNAUTHORIZED',
            'published_year' => 2024,
            'stock' => 5,
        ];

        $this->postJson('/api/books', $payload)
            ->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function authenticated_user_can_create_book()
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Authorized Book',
            'author' => 'Known',
            'isbn' => 'ISBN-AUTHORIZED',
            'published_year' => 2024,
            'stock' => 5,
        ];

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/books', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['title' => 'Authorized Book']);
    }
}
