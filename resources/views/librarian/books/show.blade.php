<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.books.index') }}"
            class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Book Info -->
        <div class="lg:col-span-1">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="aspect-[3/4] bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                    @if ($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-icon name="fas-book" class="w-20 h-20 text-gray-400 dark:text-gray-500" />
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <span
                        class="inline-block px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 mb-3">
                        {{ $book->category->name }}
                    </span>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $book->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $book->author }}</p>

                    <div class="space-y-3 text-sm">
                        @if ($book->isbn)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">ISBN</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $book->isbn }}</span>
                            </div>
                        @endif
                        @if ($book->publisher)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Penerbit</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $book->publisher }}</span>
                            </div>
                        @endif
                        @if ($book->publish_year)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Tahun</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $book->publish_year }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Total</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $book->total_copies }} eksemplar</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Tersedia</span>
                            <span
                                class="{{ $book->available_copies > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                {{ $book->available_copies }} eksemplar
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('librarian.books.edit', $book) }}"
                            class="flex-1 text-center py-2.5 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-xl font-medium hover:bg-emerald-200 dark:hover:bg-emerald-800 transition-colors">
                            {{ __('Edit') }}
                        </a>
                        <form method="POST" action="{{ route('librarian.books.destroy', $book) }}" class="flex-1"
                            onsubmit="return confirm('{{ __('Yakin ingin menghapus buku ini?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-xl font-medium hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                {{ __('Hapus') }}
                            </button>
                        </form>
                    </div>
                    @if ($book->hasPdf())
                        <a href="{{ route('book.read', $book) }}" target="_blank"
                            class="mt-3 w-full inline-flex items-center justify-center gap-2 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all shadow">
                            <x-icon name="fas-book-open" class="w-4 h-4" />
                            {{ __('Baca Online') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Borrowing History -->
        <div class="lg:col-span-2">
            @if ($book->description)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">{{ __('Deskripsi') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $book->description }}</p>
                </div>
            @endif

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('Riwayat Peminjaman') }}</h2>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($book->borrowings()->latest()->take(10)->get() as $borrowing)
                        <div
                            class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $borrowing->member->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $borrowing->borrow_date->format('d M Y') }}
                                    @if ($borrowing->return_date)
                                        → {{ $borrowing->return_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                @switch($borrowing->status)
                                    @case('borrowed') 
                                        @if ($borrowing->isOverdue())
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @endif
                                        @break
                                    @case('returned') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                @endswitch
                            ">
                                @if ($borrowing->status === 'borrowed' && $borrowing->isOverdue())
                                    {{ __('Terlambat') }}
                                @else
                                    {{ $borrowing->status === 'borrowed' ? __('Dipinjam') : __('Dikembalikan') }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <x-icon name="fas-history" class="w-10 h-10 mx-auto mb-2 opacity-50" />
                            <p>{{ __('Belum ada riwayat peminjaman') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
