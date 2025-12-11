<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Daftar Denda') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola denda keterlambatan') }}</p>
    </div>

    <!-- Summary -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80">{{ __('Total Denda Belum Dibayar') }}</p>
                <p class="text-3xl font-bold mt-1">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl">
                <x-icon name="fas-money-bill" class="w-8 h-8" />
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Semua Status') }}</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>{{ __('Belum Bayar') }}</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('Sudah Bayar') }}</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Anggota') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Buku') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Terlambat') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Jumlah') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($fines as $fine)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $fine->borrowing->member->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $fine->borrowing->member->member_id }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300 max-w-xs truncate">{{ $fine->borrowing->book->title }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $fine->days_overdue }} {{ __('hari') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $fine->formattedAmount() }}</td>
                            <td class="px-6 py-4">
                                @if($fine->is_paid)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ __('Lunas') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ __('Belum Bayar') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$fine->is_paid)
                                        <form method="POST" action="{{ route('librarian.fines.pay', $fine) }}"
                                              onsubmit="return confirm('{{ __('Konfirmasi pembayaran denda?') }}')">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium hover:bg-green-200 dark:hover:bg-green-800 transition-colors">
                                                <x-icon name="fas-check" class="w-3 h-3" />
                                                {{ __('Bayar') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-icon name="fas-check-circle" class="w-12 h-12 mx-auto mb-3 text-green-500" />
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Tidak ada denda') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($fines->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $fines->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
