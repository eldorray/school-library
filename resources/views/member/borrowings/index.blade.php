<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Riwayat Peminjaman') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Daftar buku yang pernah Anda pinjam') }}</p>
    </div>

    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($borrowings as $borrowing)
                <div class="p-4 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div
                        class="w-14 h-18 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if ($borrowing->book->cover_image)
                            <img src="{{ Storage::url($borrowing->book->cover_image) }}"
                                class="w-full h-full object-cover">
                        @else
                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowing->book->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->book->author }}</p>
                        <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                            <span>{{ __('Dipinjam:') }} {{ $borrowing->borrow_date->format('d M Y') }}</span>
                            @if ($borrowing->return_date)
                                <span>{{ __('Dikembalikan:') }} {{ $borrowing->return_date->format('d M Y') }}</span>
                            @else
                                <span class="{{ $borrowing->isOverdue() ? 'text-red-600 dark:text-red-400' : '' }}">
                                    {{ __('Jatuh tempo:') }} {{ $borrowing->due_date->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if ($borrowing->status === 'returned') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($borrowing->isOverdue())
                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif
                        ">
                            @if ($borrowing->status === 'returned')
                                {{ __('Dikembalikan') }}
                            @elseif($borrowing->isOverdue())
                                {{ __('Terlambat') }}
                            @else
                                {{ __('Dipinjam') }}
                            @endif
                        </span>
                        @if ($borrowing->fine)
                            <span
                                class="text-xs {{ $borrowing->fine->is_paid ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ __('Denda:') }} {{ $borrowing->fine->formattedAmount() }}
                                {{ $borrowing->fine->is_paid ? '✓' : '' }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <x-icon name="fas-book-open" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                    <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Belum ada riwayat peminjaman') }}</p>
                    <a href="{{ route(auth()->user()->role . '.catalog.index') }}"
                        class="inline-flex items-center gap-2 mt-4 text-indigo-600 dark:text-indigo-400 hover:underline">
                        <x-icon name="fas-search" class="w-4 h-4" />
                        {{ __('Jelajahi katalog buku') }}
                    </a>
                </div>
            @endforelse
        </div>
        @if ($borrowings instanceof \Illuminate\Pagination\LengthAwarePaginator && $borrowings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
