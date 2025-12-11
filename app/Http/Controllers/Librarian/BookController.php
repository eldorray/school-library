<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Imports\BooksImport;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * Hapus buku dari database.
     * Tidak bisa menghapus buku yang sedang dipinjam.
     */
    public function destroy(Book $book): RedirectResponse
    {
        if ($book->borrowings()->where('status', Borrowing::STATUS_BORROWED)->exists()) {
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

    /**
     * Bulk delete books (Admin only)
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:books,id'],
        ]);

        $skipped = 0;
        $deleted = 0;

        foreach ($validated['ids'] as $id) {
            $book = Book::find($id);
            
            if ($book && $book->borrowings()->where('status', Borrowing::STATUS_BORROWED)->exists()) {
                $skipped++;
                continue;
            }

            if ($book) {
                if ($book->cover_image) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                if ($book->pdf_file) {
                    Storage::disk('public')->delete($book->pdf_file);
                }
                $book->delete();
                $deleted++;
            }
        }

        $message = "{$deleted} buku berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} buku dilewati karena sedang dipinjam.";
        }

        return redirect()->route('librarian.books.index')->with('status', $message);
    }

    /**
     * Show the import form
     */
    public function showImportForm(): View
    {
        $categories = Category::all();
        return view('librarian.books.import', compact('categories'));
    }

    /**
     * Handle the Excel import
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'], // max 10MB
        ]);

        $import = new BooksImport();
        $import->import($request->file('file'));

        $failures = $import->failures();
        $successCount = $import->getSuccessCount();

        if ($failures->isNotEmpty()) {
            $errorMessages = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $errors = implode(', ', $failure->errors());
                $errorMessages[] = "Baris {$row}: {$errors}";
            }

            return redirect()->route('librarian.books.import')
                ->with('status', "{$successCount} buku berhasil diimport.")
                ->with('import_errors', $errorMessages);
        }

        return redirect()->route('librarian.books.index')
            ->with('status', "{$successCount} buku berhasil diimport dari Excel.");
    }

    /**
     * Download import template
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $templatePath = storage_path('app/templates/book_import_template.xlsx');
        
        return response()->download($templatePath, 'template_import_buku.xlsx');
    }
}

