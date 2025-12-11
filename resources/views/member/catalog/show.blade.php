<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route(auth()->user()->role . '.catalog.index') }}"
            class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali ke Katalog') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Book Cover & Info -->
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

                    <div class="flex items-center gap-2 mb-4">
                        @if ($book->available_copies > 0)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <x-icon name="fas-check-circle" class="w-4 h-4 mr-1" />
                                {{ __('Tersedia') }} ({{ $book->available_copies }})
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <x-icon name="fas-times-circle" class="w-4 h-4 mr-1" />
                                {{ __('Tidak Tersedia') }}
                            </span>
                        @endif
                    </div>

                    @php
                        $user = auth()->user();
                        $pendingBorrowing = $user->member
                            ? \App\Models\Borrowing::where('member_id', $user->member->id)
                                ->where('book_id', $book->id)
                                ->where('status', 'pending')
                                ->first()
                            : null;
                        $activeBorrowing = $user->member
                            ? \App\Models\Borrowing::where('member_id', $user->member->id)
                                ->where('book_id', $book->id)
                                ->where('status', 'borrowed')
                                ->first()
                            : null;
                    @endphp

                    {{-- Active Borrowing Info --}}
                    @if ($activeBorrowing)
                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/30 rounded-xl text-sm text-green-700 dark:text-green-300">
                            <div class="flex items-center gap-2 mb-2">
                                <x-icon name="fas-check-circle" class="w-5 h-5" />
                                <span class="font-medium">{{ __('Buku sedang Anda pinjam') }}</span>
                            </div>
                            <p class="text-xs">
                                {{ __('Jatuh tempo:') }}
                                <strong>{{ $activeBorrowing->due_date?->format('d M Y') ?? '-' }}</strong>
                            </p>
                        </div>
                        {{-- Pending Request Info --}}
                    @elseif ($pendingBorrowing)
                        <div
                            class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-center text-sm text-amber-700 dark:text-amber-300">
                            <x-icon name="fas-clock" class="w-4 h-4 inline mr-1" />
                            {{ __('Menunggu persetujuan pustakawan') }}
                        </div>
                        {{-- Borrow Request Button --}}
                    @elseif ($book->available_copies > 0 && $user->member)
                        <form method="POST" action="{{ route($user->role . '.borrowings.store') }}">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg">
                                <x-icon name="fas-hand-holding" class="inline w-4 h-4 mr-2" />
                                {{ __('Ajukan Pinjam') }}
                            </button>
                        </form>
                    @endif

                    {{-- Reservation Button (when not available) --}}
                    @if ($book->available_copies === 0 && $user->member && !$activeBorrowing && !$pendingBorrowing)
                        <form method="POST" action="{{ route($user->role . '.reservations.store') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-medium hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg">
                                <x-icon name="fas-bookmark" class="inline w-4 h-4 mr-2" />
                                {{ __('Reservasi Buku') }}
                            </button>
                        </form>
                    @endif

                    @if ($book->hasPdf())
                        @if ($book->canRead(auth()->user()))
                            <a href="{{ route('book.read', $book) }}" target="_blank"
                                class="w-full mt-3 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg">
                                <x-icon name="fas-book-open" class="w-4 h-4" />
                                {{ __('Baca Online') }}
                            </a>
                        @else
                            <div
                                class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-center text-sm text-amber-700 dark:text-amber-300">
                                <x-icon name="fas-lock" class="w-4 h-4 inline mr-1" />
                                {{ __('Pinjam buku untuk membaca online') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Detail Buku') }}</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @if ($book->isbn)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">ISBN</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $book->isbn }}</p>
                        </div>
                    @endif
                    @if ($book->publisher)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Penerbit') }}</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $book->publisher }}</p>
                        </div>
                    @endif
                    @if ($book->publish_year)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Tahun Terbit') }}</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $book->publish_year }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Total Eksemplar') }}</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $book->total_copies }}</p>
                    </div>
                </div>
            </div>

            @if ($book->description)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">{{ __('Deskripsi') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $book->description }}</p>
                </div>
            @endif

            <!-- Related Books -->
            @if ($relatedBooks->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Buku Terkait') }}</h2>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($relatedBooks as $related)
                            <a href="{{ route(auth()->user()->role . '.catalog.show', $related) }}" class="group">
                                <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    @if ($related->cover_image)
                                        <img src="{{ Storage::url($related->cover_image) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400" />
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                                    {{ $related->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
