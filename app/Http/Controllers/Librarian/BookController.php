<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('librarian.books.index', compact('books', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('librarian.books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publish_year' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'pdf_file' => ['nullable', 'mimes:pdf', 'max:51200'], // max 50MB
            'total_copies' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('books', 'public');
        }

        $validated['available_copies'] = $validated['total_copies'];

        Book::create($validated);

        return redirect()->route('librarian.books.index')
            ->with('status', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'borrowings.member.user']);
        return view('librarian.books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::all();
        return view('librarian.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $book->id],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publish_year' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'pdf_file' => ['nullable', 'mimes:pdf', 'max:51200'], // max 50MB
            'total_copies' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            // Delete old PDF
            if ($book->pdf_file) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $validated['pdf_file'] = $request->file('pdf_file')->store('books', 'public');
        }

        // Adjust available copies based on total change
        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        $book->update($validated);

        return redirect()->route('librarian.books.index')
            ->with('status', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->borrowings()->where('status', 'borrowed')->exists()) {
            return redirect()->route('librarian.books.index')
                ->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('librarian.books.index')
            ->with('status', 'Buku berhasil dihapus.');
    }
}
