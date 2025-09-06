<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => 'required',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return response()->json($user, 201);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return ['token' => $user->createToken('api-token')->plainTextToken];
});

// Public: list buku (bisa diakses tanpa login)
Route::get('/books', [BookController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected Routes (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // CRUD buku
    Route::apiResource('books', BookController::class)->except(['index']);

    // Peminjaman
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/{user_id}', [LoanController::class, 'show']);
    Route::put('/loans/{loanId}/return', [LoanController::class, 'returnBook']);
});
