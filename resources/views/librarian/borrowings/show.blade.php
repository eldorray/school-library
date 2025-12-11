<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.borrowings.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali') }}
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Peminjaman') }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <!-- Status Badge -->
            <div class="mb-6 text-center">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($borrowing->status === 'returned')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif($borrowing->isOverdue())
                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @else
                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @endif
                ">
                    @if($borrowing->status === 'returned')
                        <x-icon name="fas-check-circle" class="w-4 h-4 mr-2" />
                        {{ __('Dikembalikan') }}
                    @elseif($borrowing->isOverdue())
                        <x-icon name="fas-exclamation-triangle" class="w-4 h-4 mr-2" />
                        {{ __('Terlambat') }} {{ $borrowing->daysOverdue() }} {{ __('hari') }}
                    @else
                        <x-icon name="fas-clock" class="w-4 h-4 mr-2" />
                        {{ __('Sedang Dipinjam') }}
                    @endif
                </span>
            </div>

            <div class="space-y-6">
                <!-- Book Info -->
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="w-16 h-20 bg-gray-200 dark:bg-gray-600 rounded-lg flex-shrink-0 flex items-center justify-center">
                        @if($borrowing->book->cover_image)
                            <img src="{{ Storage::url($borrowing->book->cover_image) }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400" />
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $borrowing->book->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $borrowing->book->author }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $borrowing->book->category->name }}</p>
                    </div>
                </div>

                <!-- Member Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Peminjam') }}</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowing->member->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $borrowing->member->member_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Diproses oleh') }}</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowing->issuedBy->name }}</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tanggal Pinjam') }}</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Jatuh Tempo') }}</p>
                        <p class="font-medium {{ $borrowing->isOverdue() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                            {{ $borrowing->due_date->format('d M Y') }}
                        </p>
                    </div>
                </div>

                @if($borrowing->return_date)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tanggal Kembali') }}</p>
                        <p class="font-medium text-green-600 dark:text-green-400">{{ $borrowing->return_date->format('d M Y') }}</p>
                    </div>
                    @if($borrowing->returnedTo)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Diterima oleh') }}</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowing->returnedTo->name }}</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($borrowing->fine)
                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-600 dark:text-red-400">{{ __('Denda Keterlambatan') }}</p>
                            <p class="text-lg font-bold text-red-700 dark:text-red-300">{{ $borrowing->fine->formattedAmount() }}</p>
                            <p class="text-xs text-red-600">{{ $borrowing->fine->days_overdue }} {{ __('hari x Rp 1.000') }}</p>
                        </div>
                        @if($borrowing->fine->is_paid)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">{{ __('Lunas') }}</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">{{ __('Belum Bayar') }}</span>
                        @endif
                    </div>
                </div>
                @endif

                @if($borrowing->notes)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Catatan') }}</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $borrowing->notes }}</p>
                </div>
                @endif
            </div>

            @if($borrowing->status === 'borrowed')
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('librarian.borrowings.return', $borrowing) }}"
                      onsubmit="return confirm('{{ __('Konfirmasi pengembalian buku?') }}')">
                    @csrf
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-medium hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg">
                        <x-icon name="fas-check" class="inline w-4 h-4 mr-2" />
                        {{ __('Proses Pengembalian') }}
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
