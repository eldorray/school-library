<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Reservasi Saya') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Daftar buku yang Anda reservasi') }}</p>
    </div>

    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($reservations as $reservation)
                <div class="p-4 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div
                        class="w-14 h-18 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if ($reservation->book->cover_image)
                            <img src="{{ Storage::url($reservation->book->cover_image) }}"
                                class="w-full h-full object-cover">
                        @else
                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->book->title }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->book->author }}</p>
                        <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                            <span>{{ __('Dibuat:') }} {{ $reservation->reservation_date->format('d M Y') }}</span>
                            <span>{{ __('Berlaku hingga:') }} {{ $reservation->expiry_date->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @switch($reservation->status)
                                @case('pending') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 @break
                                @case('fulfilled') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                @case('cancelled') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @break
                                @case('expired') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                            @endswitch
                        ">
                            @switch($reservation->status)
                                @case('pending')
                                    {{ __('Menunggu') }}
                                @break

                                @case('fulfilled')
                                    {{ __('Terpenuhi') }}
                                @break

                                @case('cancelled')
                                    {{ __('Dibatalkan') }}
                                @break

                                @case('expired')
                                    {{ __('Kadaluarsa') }}
                                @break
                            @endswitch
                        </span>
                        @if ($reservation->status === 'pending')
                            <form method="POST"
                                action="{{ route(auth()->user()->role . '.reservations.destroy', $reservation) }}"
                                onsubmit="return confirm('{{ __('Batalkan reservasi ini?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <x-icon name="fas-times" class="w-4 h-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="p-12 text-center">
                        <x-icon name="fas-bookmark" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                        <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Belum ada reservasi') }}</p>
                        <a href="{{ route(auth()->user()->role . '.catalog.index') }}"
                            class="inline-flex items-center gap-2 mt-4 text-indigo-600 dark:text-indigo-400 hover:underline">
                            <x-icon name="fas-search" class="w-4 h-4" />
                            {{ __('Jelajahi katalog buku') }}
                        </a>
                    </div>
                @endforelse
            </div>
            @if ($reservations instanceof \Illuminate\Pagination\LengthAwarePaginator && $reservations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </x-layouts.app>
