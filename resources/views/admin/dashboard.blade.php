<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Admin Dashboard') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Selamat datang, ') }}{{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-white/80 text-sm font-medium">{{ __('Total Pengguna') }}</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <x-icon name="fas-users" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 text-sm text-white/70">
                <span class="inline-flex items-center">
                    <x-icon name="fas-arrow-up" class="w-3 h-3 mr-1" />
                    {{ __('Aktif hari ini') }}
                </span>
            </div>
        </div>

        <!-- Total Books -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-white/80 text-sm font-medium">{{ __('Total Buku') }}</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_books']) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <x-icon name="fas-book" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 text-sm text-white/70">
                <span class="inline-flex items-center">
                    <x-icon name="fas-layer-group" class="w-3 h-3 mr-1" />
                    {{ __('Koleksi perpustakaan') }}
                </span>
            </div>
        </div>

        <!-- Total Members -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-white/80 text-sm font-medium">{{ __('Total Anggota') }}</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_members']) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <x-icon name="fas-id-card" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 text-sm text-white/70">
                <span class="inline-flex items-center">
                    <x-icon name="fas-user-check" class="w-3 h-3 mr-1" />
                    {{ __('Guru & Siswa') }}
                </span>
            </div>
        </div>

        <!-- Active Borrowings -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-white/80 text-sm font-medium">{{ __('Peminjaman Aktif') }}</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($stats['active_borrowings']) }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <x-icon name="fas-hand-holding" class="w-6 h-6" />
                </div>
            </div>
            <div class="mt-4 text-sm text-white/70">
                @if ($stats['overdue_borrowings'] > 0)
                    <span class="inline-flex items-center text-yellow-200">
                        <x-icon name="fas-exclamation-triangle" class="w-3 h-3 mr-1" />
                        {{ $stats['overdue_borrowings'] }} {{ __('terlambat') }}
                    </span>
                @else
                    <span class="inline-flex items-center">
                        <x-icon name="fas-check-circle" class="w-3 h-3 mr-1" />
                        {{ __('Semua tepat waktu') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Pengguna Terbaru') }}</h2>
                <a href="{{ route('admin.users.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('Lihat Semua') }}
                </a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentUsers as $user)
                    <div
                        class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                {{ $user->initials() }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($user->role)
                                @case('admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                                @case('librarian') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                @case('teacher') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                @case('student') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 @break
                            @endswitch
                        ">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-users" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>{{ __('Belum ada pengguna') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Login Logs -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Aktivitas Login') }}</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('10 Terbaru') }}
                </span>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($recentLogins as $log)
                    <div class="px-6 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-semibold text-xs">
                                {{ $log->user->initials() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 text-sm truncate">
                                        {{ $log->user->name }}</p>
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                                        @switch($log->user->role)
                                            @case('admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                                            @case('librarian') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                            @case('teacher') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                            @case('student') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 @break
                                        @endswitch
                                    ">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    <span class="inline-flex items-center gap-1">
                                        <x-icon name="fas-globe" class="w-3 h-3" />
                                        {{ $log->ip_address }}
                                    </span>
                                    <span>•</span>
                                    <span class="inline-flex items-center gap-1">
                                        <x-icon name="fas-laptop" class="w-3 h-3" />
                                        {{ $log->browser }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $log->logged_in_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-clock-rotate-left" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>{{ __('Belum ada aktivitas login') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Borrowings -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Peminjaman Terbaru') }}</h2>
                <a href="{{ route('librarian.borrowings.index') }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ __('Lihat Semua') }}
                </a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentBorrowings as $borrowing)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $borrowing->book->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $borrowing->member->user->name }} •
                                    {{ $borrowing->borrow_date->format('d M Y') }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                    {{ ucfirst($borrowing->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <x-icon name="fas-book" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>{{ __('Belum ada peminjaman') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Aksi Cepat') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}"
                class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:shadow-lg transition-all duration-300 group">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <x-icon name="fas-user-plus" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tambah User') }}</span>
            </a>

            <a href="{{ route('librarian.books.create') }}"
                class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-400 hover:shadow-lg transition-all duration-300 group">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <x-icon name="fas-book-medical" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tambah Buku') }}</span>
            </a>

            <a href="{{ route('librarian.members.create') }}"
                class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-400 hover:shadow-lg transition-all duration-300 group">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <x-icon name="fas-id-card-clip" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tambah Anggota') }}</span>
            </a>

            <a href="{{ route('librarian.borrowings.create') }}"
                class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-rose-500 dark:hover:border-rose-400 hover:shadow-lg transition-all duration-300 group">
                <div
                    class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <x-icon name="fas-hand-holding" class="w-6 h-6 text-rose-600 dark:text-rose-400" />
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Peminjaman Baru') }}</span>
            </a>
        </div>
    </div>
</x-layouts.app>
