<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'isbn',
        'category_id',
        'publisher_id',
        'publication_year',
        'language',
        'pages',
        'synopsis',
        'cover_image',
        'cover_gradient',
        'cover_color',
        'is_physical',
        'is_digital',
        'format_type',
        'shelf_location',
        'total_stock',
        'available_stock',
        'borrow_count',
        'rating',
        'rating_count',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'is_physical' => 'boolean',
            'is_digital' => 'boolean',
            'publication_year' => 'integer',
            'pages' => 'integer',
            'total_stock' => 'integer',
            'available_stock' => 'integer',
            'borrow_count' => 'integer',
            'rating' => 'float',
            'rating_count' => 'integer',
            'tags' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class);
    }

    public function availableCopies(): HasMany
    {
        return $this->hasMany(BookCopy::class)->where('status', 'available');
    }

    public function ebook(): HasOne
    {
        return $this->hasOne(Ebook::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
