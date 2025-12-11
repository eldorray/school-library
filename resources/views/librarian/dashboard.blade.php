<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard Pustakawan') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Ringkasan operasional perpustakaan hari ini') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <x-icon name="fas-book" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_books']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total Buku') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center">
                    <x-icon name="fas-check-circle" class="w-5 h-5 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['available_books']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Tersedia') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                    <x-icon name="fas-users" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_members']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Anggota') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                    <x-icon name="fas-hand-holding" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['active_borrowings']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Dipinjam') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900 flex items-center justify-center">
                    <x-icon name="fas-exclamation-triangle" class="w-5 h-5 text-red-600 dark:text-red-400" />
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['overdue_borrowings']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Terlambat') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900 flex items-center justify-center">
                    <x-icon name="fas-money-bill" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($stats['unpaid_fines'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Denda') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Overdue Alert -->
        @if($overdue->count() > 0)
        <div class="lg:col-span-3 bg-red-50 dark:bg-red-900/20 rounded-2xl border border-red-200 dark:border-red-800 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900 flex items-center justify-center flex-shrink-0">
                    <x-icon name="fas-bell" class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-red-800 dark:text-red-200 mb-2">{{ __('Peminjaman Terlambat') }}</h3>
                    <div class="space-y-2">
                        @foreach($overdue->take(3) as $borrowing)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-red-700 dark:text-red-300">
                                    {{ $borrowing->member->user->name }} - {{ $borrowing->book->title }}
                                </span>
                                <span class="text-red-600 dark:text-red-400 font-medium">
                                    {{ $borrowing->daysOverdue() }} {{ __('hari') }}
                                </span>
                            </div>
                        @endforeach
                        @if($overdue->count() > 3)
                            <a href="{{ route('librarian.borrowings.index', ['status' => 'overdue']) }}" class="text-sm text-red-600 dark:text-red-400 hover:underline">
                                {{ __('Lihat semua') }} ({{ $overdue->count() }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Due Soon -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <x-icon name="fas-clock" class="w-5 h-5 text-amber-500" />
                    {{ __('Jatuh Tempo Segera') }}
                </h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($dueSoon as $borrowing)
                    <div class="px-6 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $borrowing->book->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $borrowing->member->user->name }} • {{ $borrowing->due_date->format('d M') }}
                        </p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-check-circle" class="w-8 h-8 mx-auto mb-2 text-green-500" />
                        <p>{{ __('Tidak ada yang jatuh tempo') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Borrowings -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <x-icon name="fas-calendar-day" class="w-5 h-5 text-blue-500" />
                    {{ __('Peminjaman Hari Ini') }}
                </h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($todayBorrowings as $borrowing)
                    <div class="px-6 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $borrowing->book->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->member->user->name }}</p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-inbox" class="w-8 h-8 mx-auto mb-2 opacity-50" />
                        <p>{{ __('Belum ada peminjaman hari ini') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Aksi Cepat') }}</h2>
            <div class="space-y-3">
                <a href="{{ route('librarian.borrowings.create') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <x-icon name="fas-plus" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Peminjaman Baru') }}</span>
                </a>
                <a href="{{ route('librarian.books.create') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <x-icon name="fas-book-medical" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Tambah Buku') }}</span>
                </a>
                <a href="{{ route('librarian.members.create') }}" 
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <x-icon name="fas-user-plus" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('Daftarkan Anggota') }}</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
