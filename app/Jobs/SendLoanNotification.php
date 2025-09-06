<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable; // <- pastikan ini
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;         // <- tambahkan ini
use Illuminate\Support\Facades\Mail;

class SendLoanNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public int $bookId;
    public int $quantity;

    public function __construct(int $userId, int $bookId, int $quantity)
    {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->quantity = $quantity;
    }

    public function handle(): void
    {
        $user = User::find($this->userId);
        $book = Book::find($this->bookId);

        if (! $user || ! $book) {
            return;
        }

        $message = "Halo {$user->name}, Anda berhasil meminjam buku: {$book->title} (qty: {$this->quantity}) pada ".now()->toDateTimeString();

        // tulis ke log (MAIL_MAILER=log juga bisa dipakai)
        Log::info($message);

        // jika mau pakai mail:
        // Mail::raw($message, function($m) use ($user) {
        //     $m->to($user->email)->subject('Notifikasi Peminjaman Buku');
        // });
    }
}
