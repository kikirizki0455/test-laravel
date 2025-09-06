<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'published_year',
        'isbn',
        'stock',
    ];

    /**
     * Many-to-many relation ke User melalui pivot book_loans
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_loans')
                    ->withPivot(['id', 'quantity', 'borrowed_at', 'returned_at'])
                    ->withTimestamps();
    }
}
