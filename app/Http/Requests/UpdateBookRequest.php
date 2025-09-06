<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Book;

/**
 * @method \Illuminate\Routing\Route|object|string|null route($param = null)
 */
class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil parameter route 'book' (bisa berupa model atau id)
        $routeBook = $this->route('book');

        $bookId = null;
        if ($routeBook instanceof Book) {
            $bookId = $routeBook->id;
        } elseif (is_numeric($routeBook)) {
            $bookId = (int) $routeBook;
        }

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|digits:4|integer',
            'isbn' => [
                'required',
                'string',
                'max:50',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'stock' => 'required|integer|min:0',
        ];
    }
}
