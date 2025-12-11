<?php

use App\Http\Controllers\Settings;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Librarian;
use App\Http\Controllers\Member;
use App\Http\Controllers\BookReaderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect to role-specific dashboard
Route::get('dashboard', function () {
    $user = auth()->user();
    return redirect()->route($user->dashboardRoute());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    // Book Reader - for all authenticated users
    Route::get('/read/{book}', [BookReaderController::class, 'show'])->name('book.read');
    Route::get('/read/{book}/pdf', [BookReaderController::class, 'stream'])->name('book.stream');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', Admin\UserController::class);
    
    // Bulk Delete Routes (Admin only)
    Route::delete('/books/bulk-delete', [Librarian\BookController::class, 'bulkDestroy'])->name('books.bulk-destroy');
    Route::delete('/members/bulk-delete', [Librarian\MemberController::class, 'bulkDestroy'])->name('members.bulk-destroy');
});

// Librarian Routes
Route::middleware(['auth', 'role:librarian,admin'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard', [Librarian\DashboardController::class, 'index'])->name('dashboard');
    
    // Book Import Routes (must be before resource)
    Route::get('/books/import', [Librarian\BookController::class, 'showImportForm'])->name('books.import');
    Route::post('/books/import', [Librarian\BookController::class, 'import'])->name('books.import.store');
    Route::get('/books/import/template', [Librarian\BookController::class, 'downloadTemplate'])->name('books.import.template');
    
    Route::resource('books', Librarian\BookController::class);
    Route::resource('categories', Librarian\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('members', Librarian\MemberController::class);
    Route::resource('borrowings', Librarian\BorrowingController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/borrowings/{borrowing}/return', [Librarian\BorrowingController::class, 'returnBook'])->name('borrowings.return');
    Route::post('/borrowings/{borrowing}/approve', [Librarian\BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{borrowing}/reject', [Librarian\BorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::get('/fines', [Librarian\FineController::class, 'index'])->name('fines.index');
    Route::post('/fines/{fine}/pay', [Librarian\FineController::class, 'markPaid'])->name('fines.pay');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', [Member\CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book}', [Member\CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/my-borrowings', [Member\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/my-reservations', [Member\ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [Member\ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}', [Member\ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', [Member\CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book}', [Member\CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/my-borrowings', [Member\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrow', [Member\BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/my-reservations', [Member\ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [Member\ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}', [Member\ReservationController::class, 'destroy'])->name('reservations.destroy');
});

require __DIR__.'/auth.php';
