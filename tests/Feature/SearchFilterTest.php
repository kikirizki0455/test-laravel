<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_books_by_author()
    {
        Book::factory()->create(['author' => 'Special Author']);
        Book::factory()->count(5)->create();

        $this->getJson('/api/books?author=Special')
            ->assertJsonFragment(['author' => 'Special Author']);
    }

    /** @test */
    public function can_filter_books_by_year()
    {
        Book::factory()->create(['published_year' => 2022]);
        Book::factory()->count(5)->create();

        $this->getJson('/api/books?year=2022')
            ->assertJsonFragment(['published_year' => 2022]);
    }

    /** @test */
    public function can_filter_books_by_title()
    {
        Book::factory()->create(['title' => 'Unique Title']);
        Book::factory()->count(5)->create();

        $this->getJson('/api/books?title=Unique')
            ->assertJsonFragment(['title' => 'Unique Title']);
    }
}
