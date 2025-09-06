<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendLoanNotification;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // POST /api/loans
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
    
        $quantity = $data['quantity'] ?? 1;
    
        return DB::transaction(function() use ($data, $quantity) {
            // lock row to prevent race condition
            $book = Book::where('id', $data['book_id'])->lockForUpdate()->first();
    
            if ($book->stock < $quantity) {
                return response()->json(['message'=>'Stok buku tidak cukup'], 422);
            }
    
            $book->decrement('stock', $quantity);
    
            DB::table('book_loans')->insert([
                'user_id' => $data['user_id'],
                'book_id' => $book->id,
                'quantity' => $quantity,
                'borrowed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // dispatch job (queued)
            SendLoanNotification::dispatch($data['user_id'], $book->id, $quantity);
    
            return response()->json(['message'=>'Berhasil meminjam buku'], 201);
        });
    }

    // GET /api/loans/{user_id}
    public function show($userId)
{
    $user = User::with(['books' => function ($q) {
        $q->withPivot(['id', 'quantity', 'borrowed_at', 'returned_at']);
    }])->findOrFail($userId);

    return response()->json([
        'user' => $user->only(['id','name','email']),
        'books' => $user->books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'quantity' => $book->pivot->quantity,
                'borrowed_at' => $book->pivot->borrowed_at,
                'returned_at' => $book->pivot->returned_at,
            ];
        }),
    ]);
}


    public function returnBook($loanId)
    {
        $loan = DB::table('book_loans')->where('id', $loanId)->first();
    
        if (! $loan) {
            return response()->json(['message' => 'Data peminjaman tidak ditemukan'], 404);
        }
    
        if ($loan->returned_at) {
            return response()->json(['message' => 'Buku sudah dikembalikan sebelumnya'], 422);
        }
    
        // kembalikan stok buku
        $book = Book::findOrFail($loan->book_id);
        $book->increment('stock', $loan->quantity);
    
        // update returned_at di pivot
        DB::table('book_loans')
            ->where('id', $loanId)
            ->update(['returned_at' => now()]);
    
        return response()->json(['message' => 'Buku berhasil dikembalikan']);
    }
}
