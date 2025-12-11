<x-layouts.app>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Peminjaman') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola transaksi peminjaman buku') }}</p>
        </div>
        <a href="{{ route('librarian.borrowings.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg">
            <x-icon name="fas-plus" class="w-4 h-4" />
            {{ __('Peminjaman Baru') }}
        </a>
    </div>

    @if ($pendingCount > 0)
        <div
            class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-800 flex items-center justify-center">
                <x-icon name="fas-clock" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
            </div>
            <div class="flex-1">
                <p class="font-medium text-amber-800 dark:text-amber-200">{{ $pendingCount }}
                    {{ __('permintaan pinjam menunggu persetujuan') }}</p>
                <a href="?status=pending"
                    class="text-sm text-amber-600 dark:text-amber-400 hover:underline">{{ __('Lihat semua →') }}</a>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <x-icon name="fas-search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('Cari nama peminjam atau judul buku...') }}"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
            <select name="status"
                class="sm:w-40 px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Semua') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Menunggu') }}
                </option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>{{ __('Dipinjam') }}
                </option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>
                    {{ __('Dikembalikan') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('Ditolak') }}
                </option>
            </select>
            <button type="submit"
                class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Peminjam') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Buku') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Tanggal') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Status') }}</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($borrowings as $borrowing)
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $borrowing->status === 'pending' ? 'bg-amber-50/50 dark:bg-amber-900/20' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-xs font-medium text-indigo-600 dark:text-indigo-400">
                                        {{ substr($borrowing->member->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $borrowing->member->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $borrowing->member->member_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                {{ $borrowing->book->title }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                @if ($borrowing->status === 'pending')
                                    <span
                                        class="text-amber-600 dark:text-amber-400">{{ $borrowing->created_at->format('d M Y H:i') }}</span>
                                @elseif($borrowing->borrow_date)
                                    {{ $borrowing->borrow_date->format('d M Y') }}
                                    @if ($borrowing->due_date && $borrowing->status === 'borrowed')
                                        <span
                                            class="{{ $borrowing->isOverdue() ? 'text-red-600 dark:text-red-400' : '' }}">
                                            → {{ $borrowing->due_date->format('d M Y') }}
                                        </span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    @switch($borrowing->status)
                                        @case('pending') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 @break
                                        @case('borrowed')
                                            @if ($borrowing->isOverdue())
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else
                                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @endif
                                            @break
                                        @case('returned') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('rejected') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @break
                                    @endswitch
                                ">
                                    @switch($borrowing->status)
                                        @case('pending')
                                            {{ __('Menunggu') }}
                                        @break

                                        @case('borrowed')
                                            @if ($borrowing->isOverdue())
                                                {{ __('Terlambat') }} ({{ $borrowing->daysOverdue() }} hari)
                                            @else
                                                {{ __('Dipinjam') }}
                                            @endif
                                        @break

                                        @case('returned')
                                            {{ __('Dikembalikan') }}
                                        @break

                                        @case('rejected')
                                            {{ __('Ditolak') }}
                                        @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($borrowing->status === 'pending')
                                        <form method="POST"
                                            action="{{ route('librarian.borrowings.approve', $borrowing) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                                <x-icon name="fas-check" class="w-3 h-3" />
                                                {{ __('Setujui') }}
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="document.getElementById('reject-{{ $borrowing->id }}').classList.toggle('hidden')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg text-sm font-medium hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                            <x-icon name="fas-times" class="w-3 h-3" />
                                            {{ __('Tolak') }}
                                        </button>
                                    @elseif($borrowing->status === 'borrowed')
                                        <form method="POST"
                                            action="{{ route('librarian.borrowings.return', $borrowing) }}"
                                            onsubmit="return confirm('{{ __('Konfirmasi pengembalian buku?') }}')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                                <x-icon name="fas-check" class="w-3 h-3" />
                                                {{ __('Kembalikan') }}
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('librarian.borrowings.show', $borrowing) }}"
                                        class="p-2 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <x-icon name="fas-eye" class="w-4 h-4" />
                                    </a>
                                </div>
                                @if ($borrowing->status === 'pending')
                                    <div id="reject-{{ $borrowing->id }}" class="hidden mt-2">
                                        <form method="POST"
                                            action="{{ route('librarian.borrowings.reject', $borrowing) }}"
                                            class="flex gap-2">
                                            @csrf
                                            <input type="text" name="rejection_reason"
                                                placeholder="{{ __('Alasan penolakan (opsional)') }}"
                                                class="flex-1 px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                                                {{ __('Tolak') }}
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <x-icon name="fas-hand-holding" class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('Tidak ada peminjaman') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($borrowings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $borrowings->links() }}
                </div>
            @endif
        </div>
    </x-layouts.app>
