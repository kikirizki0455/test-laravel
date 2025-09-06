<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * List buku (dengan filter & pagination).
     */
    public function index(Request $request)
    {
        $books = Book::query();
    
        // filter & search
        if ($request->filled('author')) {
            $books->where('author', 'like', "%{$request->author}%");
        }
        if ($request->filled('year')) {
            $books->where('published_year', $request->year);
        }
        if ($request->filled('title')) {
            $books->where('title', 'like', "%{$request->title}%");
        }
    
        return BookResource::collection($books->paginate(10));
    }

    /**
     * Store a new book.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return response()->json($book, 201);
    }

    /**
     * Show single book.
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Update existing book.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return response()->json($book);
    }

    /**
     * Delete book.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(null, 204);
    }

    
}
