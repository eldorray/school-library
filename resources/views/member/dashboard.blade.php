<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Selamat Datang') }},
            {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Portal Perpustakaan Sekolah') }}</p>
    </div>

    <!-- Stats -->
    @if (!empty($stats))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <x-icon name="fas-book" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $stats['active_borrowings'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Buku Dipinjam') }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <x-icon name="fas-history" class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $stats['total_borrowed'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Pernah Dipinjam') }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900 flex items-center justify-center">
                        <x-icon name="fas-bookmark" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $stats['pending_reservations'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Reservasi Aktif') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Active Borrowings -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('Buku Saya') }}</h2>
                <a href="{{ route(auth()->user()->role . '.borrowings.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('Lihat Semua') }}
                </a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($activeBorrowings as $borrowing)
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div
                            class="w-12 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if ($borrowing->book->cover_image)
                                <img src="{{ Storage::url($borrowing->book->cover_image) }}"
                                    class="w-full h-full object-cover rounded-lg">
                            @else
                                <x-icon name="fas-book" class="w-5 h-5 text-gray-400" />
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ $borrowing->book->title }}</p>
                            <p class="text-sm text-gray-500">{{ $borrowing->book->author }}</p>
                            <p
                                class="text-xs {{ $borrowing->isOverdue() ? 'text-red-600 dark:text-red-400' : 'text-gray-400' }} mt-1">
                                {{ __('Kembali:') }} {{ $borrowing->due_date->format('d M Y') }}
                                @if ($borrowing->isOverdue())
                                    ({{ __('Terlambat') }} {{ $borrowing->daysOverdue() }} {{ __('hari') }})
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-book-open" class="w-10 h-10 mx-auto mb-2 opacity-50" />
                        <p>{{ __('Tidak ada buku yang dipinjam') }}</p>
                        <a href="{{ route(auth()->user()->role . '.catalog.index') }}"
                            class="inline-flex items-center gap-1 mt-2 text-indigo-600 dark:text-indigo-400 text-sm hover:underline">
                            <x-icon name="fas-search" class="w-3 h-3" />
                            {{ __('Cari buku') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- New Books -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('Buku Terbaru') }}</h2>
                <a href="{{ route(auth()->user()->role . '.catalog.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('Lihat Katalog') }}
                </a>
            </div>
            <div class="p-4 grid grid-cols-3 gap-3">
                @forelse($newBooks as $book)
                    <a href="{{ route(auth()->user()->role . '.catalog.show', $book) }}" class="group">
                        <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden">
                            @if ($book->cover_image)
                                <img src="{{ Storage::url($book->cover_image) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-icon name="fas-book" class="w-8 h-8 text-gray-400" />
                                </div>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                            {{ $book->title }}</p>
                    </a>
                @empty
                    <div class="col-span-3 py-8 text-center text-gray-500">
                        <p>{{ __('Belum ada buku') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
