<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_book()
    {
        // Buat 1 user saja
        $user = User::factory()->create();
    
        $payload = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'isbn' => 'ISBN-TEST-123',
            'stock' => 5,
            'published_year' => 2024,
        ];
    
        // actingAs butuh 1 user, bukan collection
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/books', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Book']);
    
        $this->assertDatabaseHas('books', ['isbn' => 'ISBN-TEST-123']);
    }
    
}
