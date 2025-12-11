<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BookReaderController extends Controller
{
    /**
     * Display the book reader (flipbook style).
     */
    public function show(Book $book): View|Response
    {
        // Check if book has PDF
        if (!$book->hasPdf()) {
            abort(404, 'PDF tidak tersedia untuk buku ini.');
        }

        $user = auth()->user();

        // Check if user can read this book
        if (!$book->canRead($user)) {
            return response()->view('book-reader.access-denied', compact('book'), 403);
        }

        return view('book-reader.flipbook', compact('book'));
    }

    /**
     * Stream the PDF file (for security, we stream instead of direct URL).
     */
    public function stream(Book $book): Response
    {
        // Check if book has PDF
        if (!$book->hasPdf()) {
            abort(404);
        }

        $user = auth()->user();

        // Check if user can read this book
        if (!$book->canRead($user)) {
            abort(403);
        }

        $path = Storage::disk('public')->path($book->pdf_file);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"',
        ]);
    }
}
