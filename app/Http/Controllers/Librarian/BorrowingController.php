<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Tampilkan daftar semua peminjaman.
     * 
     * Filter yang tersedia:
     * - search: Cari berdasarkan nama peminjam atau judul buku
     * - status: Filter berdasarkan status peminjaman
     */
    public function index(Request $request): View
    {
        $query = Borrowing::with(['member.user', 'book', 'issuedBy']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member.user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($q) => $q->where('title', 'like', "%{$search}%"));
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(15)->withQueryString();

        // Hitung permintaan pending untuk notifikasi
        $pendingCount = Borrowing::where('status', Borrowing::STATUS_PENDING)->count();

        return view('librarian.borrowings.index', compact('borrowings', 'pendingCount'));
    }

    /**
     * Tampilkan form untuk membuat peminjaman baru.
     */
    public function create(): View
    {
        $members = Member::with('user')->where('is_active', true)->get();
        $books = Book::where('available_copies', '>', 0)->get();

        return view('librarian.borrowings.create', compact('members', 'books'));
    }

    /**
     * Simpan peminjaman baru.
     * Digunakan ketika pustakawan langsung meminjamkan buku ke anggota.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'due_date' => ['required', 'date', 'after:today'],
            'notes' => ['nullable', 'string'],
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $book = Book::findOrFail($validated['book_id']);

        // Cek apakah anggota bisa meminjam
        if (!$member->canBorrow()) {
            return back()->with('error', 'Anggota telah mencapai batas peminjaman atau tidak aktif.');
        }

        // Cek ketersediaan buku
        if (!$book->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        // Buat peminjaman
        Borrowing::create([
            'member_id' => $validated['member_id'],
            'book_id' => $validated['book_id'],
            'issued_by' => auth()->id(),
            'borrow_date' => now(),
            'due_date' => $validated['due_date'],
            'status' => Borrowing::STATUS_BORROWED,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Kurangi stok buku
        $book->decrementAvailable();

        return redirect()->route('librarian.borrowings.index')
            ->with('status', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Tampilkan detail peminjaman.
     */
    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['member.user', 'book', 'issuedBy', 'returnedTo', 'fine']);
        return view('librarian.borrowings.show', compact('borrowing'));
    }

    /**
     * Proses pengembalian buku.
     */
    public function returnBook(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status === Borrowing::STATUS_RETURNED) {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $borrowing->processReturn(auth()->id());

        $message = 'Pengembalian berhasil dicatat.';
        if ($borrowing->fine) {
            $message .= ' Denda: ' . $borrowing->fine->formattedAmount();
        }

        return redirect()->route('librarian.borrowings.index')
            ->with('status', $message);
    }

    /**
     * Setujui permintaan peminjaman.
     */
    public function approve(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== Borrowing::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $validated = $request->validate([
            'loan_days' => ['nullable', 'integer', 'min:1', 'max:30'],
        ]);

        $loanDays = $validated['loan_days'] ?? Borrowing::DEFAULT_LOAN_DAYS;

        $borrowing->approve(auth()->id(), $loanDays);

        return back()->with('status', 'Permintaan peminjaman disetujui.');
    }

    /**
     * Tolak permintaan peminjaman.
     */
    public function reject(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status !== Borrowing::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $borrowing->reject($validated['rejection_reason'] ?? null);

        return back()->with('status', 'Permintaan peminjaman ditolak.');
    }
}


