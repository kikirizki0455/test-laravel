<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sementara true, bisa tambahkan auth check nanti
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|digits:4|integer',
            'isbn' => 'required|string|max:50|unique:books,isbn',
            'stock' => 'required|integer|min:0',
        ];
    }
}
