<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    /**
     * Get loans of current authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = $request->query('status', 'active'); // 'active' | 'all' | 'history'

        $query = Loan::with(['book.category', 'copy'])
            ->where('user_id', $user->id);

        if ($status === 'active') {
            $query->where('status', 'borrowed');
        } elseif ($status === 'history') {
            $query->where('status', 'returned');
        }

        $loans = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'max_borrow_quota' => $user->max_borrow_quota,
            'current_borrowed' => $user->activeLoans()->count(),
            'loans' => $loans,
        ]);
    }

    /**
     * Borrow a book (Physical or Digital).
     */
    public function borrow(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = $request->user();
        $book = Book::findOrFail($validated['book_id']);

        // Check user quota
        if ($user->activeLoans()->count() >= $user->max_borrow_quota) {
            return response()->json([
                'message' => "Batas kuota peminjaman Anda ({$user->max_borrow_quota} buku) telah tercapai!",
            ], 422);
        }

        // Check if already borrowed and active
        $alreadyBorrowed = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($alreadyBorrowed) {
            return response()->json([
                'message' => 'Buku ini sudah berada dalam daftar pinjaman aktif Anda.',
            ], 422);
        }

        // Check stock
        if ($book->available_stock <= 0) {
            return response()->json([
                'message' => 'Stok buku ini sedang habis. Silakan lakukan reservasi.',
            ], 422);
        }

        // Assign physical copy if applicable
        $copy = null;
        if ($book->is_physical) {
            $copy = BookCopy::where('book_id', $book->id)
                ->where('status', 'available')
                ->first();

            if ($copy) {
                $copy->update(['status' => 'borrowed']);
            }
        }

        // Decrement book available stock & increment borrow count
        $book->decrement('available_stock');
        $book->increment('borrow_count');

        $now = Carbon::now();
        $dueDate = $now->copy()->addDays(14); // 14 days default borrowing duration

        $loan = Loan::create([
            'loan_code' => 'PINJ-' . date('Y') . '-' . strtoupper(Str::random(5)),
            'user_id' => $user->id,
            'book_id' => $book->id,
            'book_copy_id' => $copy ? $copy->id : null,
            'borrow_date' => $now->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'status' => 'borrowed',
            'extend_count' => 0,
            'max_extend' => 2,
        ]);

        return response()->json([
            'message' => 'Buku berhasil dipinjam! Batas waktu pengembalian adalah 14 hari.',
            'loan' => $loan->load(['book', 'copy']),
        ], 201);
    }

    /**
     * Extend loan due date (+7 days).
     */
    public function extend(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $loan = Loan::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'borrowed')
            ->firstOrFail();

        if ($loan->extend_count >= $loan->max_extend) {
            return response()->json([
                'message' => "Batas perpanjangan ({$loan->max_extend}x) untuk buku ini telah tercapai.",
            ], 422);
        }

        $newDueDate = Carbon::parse($loan->due_date)->addDays(7);
        $loan->update([
            'due_date' => $newDueDate->toDateString(),
            'extend_count' => $loan->extend_count + 1,
        ]);

        return response()->json([
            'message' => 'Masa pinjaman berhasil diperpanjang 7 hari hingga ' . $newDueDate->translatedFormat('d F Y'),
            'loan' => $loan,
        ]);
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $loan = Loan::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'borrowed')
            ->firstOrFail();

        // Restore copy
        if ($loan->book_copy_id) {
            $copy = BookCopy::find($loan->book_copy_id);
            if ($copy) {
                $copy->update(['status' => 'available']);
            }
        }

        // Restore book stock
        $book = Book::find($loan->book_id);
        if ($book && $book->available_stock < $book->total_stock) {
            $book->increment('available_stock');
        }

        $loan->update([
            'return_date' => Carbon::now()->toDateString(),
            'status' => 'returned',
        ]);

        return response()->json([
            'message' => 'Buku berhasil dikembalikan. Terima kasih telah menjaga koleksi perpustakaan!',
            'loan' => $loan,
        ]);
    }
}
