<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'total_members' => Member::where('is_active', true)->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', now())
                ->count(),
            'unpaid_fines' => Fine::where('is_paid', false)->sum('amount'),
        ];

        $todayBorrowings = Borrowing::with(['member.user', 'book'])
            ->whereDate('borrow_date', now())
            ->get();

        $dueSoon = Borrowing::with(['member.user', 'book'])
            ->where('status', 'borrowed')
            ->whereBetween('due_date', [now(), now()->addDays(3)])
            ->orderBy('due_date')
            ->get();

        $overdue = Borrowing::with(['member.user', 'book'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        return view('librarian.dashboard', compact('stats', 'todayBorrowings', 'dueSoon', 'overdue'));
    }
}
