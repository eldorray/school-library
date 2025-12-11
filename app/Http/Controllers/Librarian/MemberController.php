<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('librarian.members.index', compact('members'));
    }

    public function create(): View
    {
        return view('librarian.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'member_id' => ['required', 'string', 'max:50', 'unique:members'],
            'type' => ['required', Rule::in(['student', 'teacher'])],
            'class' => ['nullable', 'required_if:type,student', 'string', 'max:50'],
            'department' => ['nullable', 'required_if:type,teacher', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['type'],
        ]);

        // Create member profile
        Member::create([
            'user_id' => $user->id,
            'member_id' => $validated['member_id'],
            'type' => $validated['type'],
            'class' => $validated['class'] ?? null,
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'join_date' => now(),
            'is_active' => true,
        ]);

        return redirect()->route('librarian.members.index')
            ->with('status', 'Anggota berhasil didaftarkan.');
    }

    public function show(Member $member): View
    {
        $member->load(['user', 'borrowings.book', 'reservations.book']);
        return view('librarian.members.show', compact('member'));
    }

    public function edit(Member $member): View
    {
        $member->load('user');
        return view('librarian.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->user_id)],
            'member_id' => ['required', 'string', 'max:50', Rule::unique('members')->ignore($member->id)],
            'class' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $member->user->update(['password' => Hash::make($request->password)]);
        }

        $member->update([
            'member_id' => $validated['member_id'],
            'class' => $validated['class'] ?? null,
            'department' => $validated['department'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('librarian.members.index')
            ->with('status', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->borrowings()->where('status', 'borrowed')->exists()) {
            return redirect()->route('librarian.members.index')
                ->with('error', 'Anggota masih memiliki peminjaman aktif.');
        }

        $member->user->delete(); // Will cascade delete member

        return redirect()->route('librarian.members.index')
            ->with('status', 'Anggota berhasil dihapus.');
    }

    /**
     * Bulk delete members (Admin only)
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:members,id'],
        ]);

        $skipped = 0;
        $deleted = 0;

        foreach ($validated['ids'] as $id) {
            $member = Member::find($id);
            
            if ($member && $member->borrowings()->where('status', 'borrowed')->exists()) {
                $skipped++;
                continue;
            }

            if ($member) {
                $member->user->delete(); // Will cascade delete member
                $deleted++;
            }
        }

        $message = "{$deleted} anggota berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} anggota dilewati karena memiliki peminjaman aktif.";
        }

        return redirect()->route('librarian.members.index')->with('status', $message);
    }
}
