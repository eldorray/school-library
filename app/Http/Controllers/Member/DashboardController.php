<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $member = $user->member;

        $stats = [];
        $activeBorrowings = collect();
        $recentReservations = collect();

        if ($member) {
            $stats = [
                'active_borrowings' => $member->activeBorrowingsCount(),
                'total_borrowed' => $member->borrowings()->count(),
                'pending_reservations' => $member->reservations()->where('status', 'pending')->count(),
            ];

            $activeBorrowings = Borrowing::with('book')
                ->where('member_id', $member->id)
                ->where('status', 'borrowed')
                ->orderBy('due_date')
                ->get();

            $recentReservations = Reservation::with('book')
                ->where('member_id', $member->id)
                ->where('status', 'pending')
                ->orderBy('reservation_date', 'desc')
                ->take(5)
                ->get();
        }

        $newBooks = Book::latest()->take(6)->get();

        return view('member.dashboard', compact('stats', 'activeBorrowings', 'recentReservations', 'newBooks'));
    }
}
