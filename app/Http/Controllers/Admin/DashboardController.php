<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\LoginLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_members' => Member::count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', now())
                ->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentBorrowings = Borrowing::with(['member.user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $recentLogins = LoginLog::with('user')
            ->latest('logged_in_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBorrowings', 'recentLogins'));
    }
}
