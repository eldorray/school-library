<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $member = $user->member;

        $borrowings = collect();

        if ($member) {
            $borrowings = Borrowing::with(['book', 'fine'])
                ->where('member_id', $member->id)
                ->latest()
                ->paginate(10);
        }

        return view('member.borrowings.index', compact('borrowings'));
    }

    /**
     * Submit a borrow request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $user = auth()->user();
        $member = $user->member;

        if (!$member) {
            return back()->with('error', 'Anda belum terdaftar sebagai anggota perpustakaan.');
        }

        $book = Book::findOrFail($validated['book_id']);

        // Check if already has pending or active borrowing for this book
        $existingBorrowing = Borrowing::where('member_id', $member->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->exists();

        if ($existingBorrowing) {
            return back()->with('error', 'Anda sudah memiliki peminjaman aktif atau permintaan pending untuk buku ini.');
        }

        // Check book availability
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Buku tidak tersedia saat ini.');
        }

        // Create pending borrow request
        Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);

        return redirect()->route($user->role . '.catalog.show', $book)
            ->with('status', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan pustakawan.');
    }
}

