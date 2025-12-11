<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FineController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fine::with(['borrowing.member.user', 'borrowing.book']);

        if ($request->filled('status')) {
            $query->where('is_paid', $request->status === 'paid');
        }

        $fines = $query->latest()->paginate(15)->withQueryString();
        $totalUnpaid = Fine::where('is_paid', false)->sum('amount');

        return view('librarian.fines.index', compact('fines', 'totalUnpaid'));
    }

    public function markPaid(Fine $fine): RedirectResponse
    {
        $fine->markAsPaid();

        return redirect()->route('librarian.fines.index')
            ->with('status', 'Denda berhasil dibayar.');
    }
}
