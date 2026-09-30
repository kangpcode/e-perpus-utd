<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Get paginated & filtered catalog books.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Book::with(['category', 'authors', 'publisher', 'ebook'])
            ->withCount('reviews');

        // Search filter (Title, ISBN, Synopsis, Author)
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Category filter
        if ($categorySlug = $request->query('category')) {
            if ($categorySlug !== 'all') {
                $query->whereHas('category', function ($catQuery) use ($categorySlug) {
                    $catQuery->where('slug', $categorySlug);
                });
            }
        }

        // Format filter ('physical', 'pdf', 'epub')
        if ($format = $request->query('format')) {
            if ($format === 'physical') {
                $query->where('is_physical', true);
            } elseif ($format === 'pdf') {
                $query->whereIn('format_type', ['pdf', 'hybrid']);
            } elseif ($format === 'epub') {
                $query->where('format_type', 'epub');
            }
        }

        // Availability filter
        if ($request->boolean('available_only')) {
            $query->where('available_stock', '>', 0);
        }

        // Sorting
        $sortBy = $request->query('sort', 'popular'); // 'popular' | 'latest' | 'rating'
        if ($sortBy === 'latest') {
            $query->orderBy('publication_year', 'desc')->orderBy('id', 'desc');
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy('borrow_count', 'desc');
        }

        $perPage = (int) $request->query('per_page', 12);
        $books = $query->paginate($perPage);

        return response()->json($books);
    }

    /**
     * Get single book details.
     */
    public function show(string $slug): JsonResponse
    {
        $book = Book::with([
            'category',
            'authors',
            'publisher',
            'copies',
            'ebook',
            'reviews.user:id,name,avatar',
        ])
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        return response()->json($book);
    }

    /**
     * Get all categories with book counts.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('books')->get();

        return response()->json($categories);
    }

    /**
     * Store a newly created book (Pustakawan / Admin only).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:30|unique:books,isbn',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'author_name' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:50',
            'pages' => 'required|integer|min:1',
            'synopsis' => 'required|string',
            'format_type' => 'required|in:physical,pdf,epub,hybrid',
            'shelf_location' => 'nullable|string|max:100',
            'total_stock' => 'required|integer|min:1',
            'cover_gradient' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']) . '-' . Str::random(4);

        // Find or create author
        $author = Author::firstOrCreate(
            ['name' => $validated['author_name']],
            ['slug' => Str::slug($validated['author_name'])]
        );

        $isPhysical = in_array($validated['format_type'], ['physical', 'hybrid']);
        $isDigital = in_array($validated['format_type'], ['pdf', 'epub', 'hybrid']);

        $gradient = $validated['cover_gradient'] ?? 'from-blue-700 via-indigo-800 to-blue-950';

        $book = Book::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'isbn' => $validated['isbn'],
            'category_id' => $validated['category_id'],
            'publisher_id' => $validated['publisher_id'] ?? null,
            'publication_year' => $validated['publication_year'],
            'language' => $validated['language'] ?? 'Bahasa Indonesia',
            'pages' => $validated['pages'],
            'synopsis' => $validated['synopsis'],
            'cover_gradient' => $gradient,
            'cover_color' => '#1E4FA3',
            'is_physical' => $isPhysical,
            'is_digital' => $isDigital,
            'format_type' => $validated['format_type'],
            'shelf_location' => $validated['shelf_location'] ?? ($isPhysical ? 'Lantai 2 - Rak Koleksi' : 'Digital Repository'),
            'total_stock' => $validated['total_stock'],
            'available_stock' => $validated['total_stock'],
            'borrow_count' => 0,
            'rating' => 5.00,
            'rating_count' => 0,
            'tags' => ['Digitech', 'Akademik', 'Koleksi Baru'],
        ]);

        $book->authors()->sync([$author->id]);

        // Generate physical copies
        if ($isPhysical) {
            $prefix = strtoupper(substr($slug, 0, 3));
            for ($i = 1; $i <= $validated['total_stock']; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => "{$prefix}-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'condition' => 'good',
                    'status' => 'available',
                ]);
            }
        }

        // Generate ebook record if digital
        if ($isDigital) {
            Ebook::create([
                'book_id' => $book->id,
                'file_path' => 'ebooks/' . $book->slug . '.' . ($validated['format_type'] === 'epub' ? 'epub' : 'pdf'),
                'file_size' => '12.5 MB',
                'format' => $validated['format_type'] === 'epub' ? 'epub' : 'pdf',
                'sample_content' => [
                    "BAB 1: Pengantar {$book->title}",
                    $book->synopsis,
                    "Hak Cipta dilindungi undang-undang dan kebijakan perpustakaan Digitech University."
                ],
                'drm_watermark_enabled' => true,
            ]);
        }

        return response()->json([
            'message' => 'Buku baru berhasil ditambahkan ke katalog Digitech University!',
            'book' => $book->load(['category', 'authors', 'publisher', 'copies', 'ebook']),
        ], 201);
    }

    /**
     * Delete book from catalog.
     */
    public function destroy(int $id): JsonResponse
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'message' => 'Koleksi buku berhasil dihapus dari sistem.',
        ]);
    }

    /**
     * Add review & rating by student or faculty.
     */
    public function addReview(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $book = Book::findOrFail($id);

        $review = Review::updateOrCreate(
            ['book_id' => $book->id, 'user_id' => $user->id],
            ['rating' => $validated['rating'], 'comment' => $validated['comment']]
        );

        // Recalculate book average rating
        $avgRating = $book->reviews()->avg('rating');
        $count = $book->reviews()->count();

        $book->update([
            'rating' => round($avgRating, 2),
            'rating_count' => $count,
        ]);

        return response()->json([
            'message' => 'Ulasan dan rating Anda berhasil disimpan. Terima kasih!',
            'review' => $review->load('user:id,name'),
            'book_rating' => $book->rating,
            'book_rating_count' => $book->rating_count,
        ]);
    }
}
