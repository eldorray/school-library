<x-layouts.app>
    <div class="max-w-lg mx-auto py-12 text-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div
                class="w-20 h-20 mx-auto mb-6 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center">
                <x-icon name="fas-lock" class="w-10 h-10 text-amber-600 dark:text-amber-400" />
            </div>

            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3">{{ __('Akses Ditolak') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                {{ __('Anda harus meminjam buku ini terlebih dahulu untuk dapat membacanya secara online.') }}
            </p>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-20 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if ($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400" />
                        @endif
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $book->title }}</p>
                        <p class="text-sm text-gray-500">{{ $book->author }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route(auth()->user()->role . '.catalog.show', $book) }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg">
                    <x-icon name="fas-eye" class="w-4 h-4" />
                    {{ __('Lihat Detail Buku') }}
                </a>
                <a href="{{ url()->previous() }}"
                    class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                    {{ __('Kembali') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
