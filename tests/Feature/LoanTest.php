<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendLoanNotification;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_borrow_book()
    {
        Queue::fake(); // mencegah job benar2 dikirim
        $user = User::factory()->create();
        $book = Book::factory()->create(['stock' => 3]);

        Sanctum::actingAs($user, ['*']);

        $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 1,
        ])->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 2,
        ]);

        $this->assertDatabaseHas('book_loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        Queue::assertPushed(SendLoanNotification::class);
    }

    public function test_cannot_borrow_when_stock_zero()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['stock' => 0]);

        Sanctum::actingAs($user, ['*']);

        $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 1,
        ])->assertStatus(422);

        $this->assertDatabaseMissing('book_loans', [
            'book_id' => $book->id,
        ]);
    }
}
