<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $member = $user->member;

        $reservations = collect();

        if ($member) {
            $reservations = Reservation::with('book')
                ->where('member_id', $member->id)
                ->latest()
                ->paginate(10);
        }

        return view('member.reservations.index', compact('reservations'));
    }

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

        // Check if already reserved
        $existingReservation = Reservation::where('member_id', $member->id)
            ->where('book_id', $book->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingReservation) {
            return back()->with('error', 'Anda sudah memiliki reservasi untuk buku ini.');
        }

        Reservation::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'reservation_date' => now(),
            'expiry_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        return back()->with('status', 'Reservasi berhasil dibuat.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $user = auth()->user();
        
        if ($reservation->member_id !== $user->member?->id) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $reservation->cancel();

        return back()->with('status', 'Reservasi berhasil dibatalkan.');
    }
}
