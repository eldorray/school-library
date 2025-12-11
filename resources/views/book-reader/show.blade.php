<x-layouts.app>
    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                    <x-icon name="fas-arrow-left" class="w-5 h-5 mr-2" />
                    {{ __('Kembali') }}
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $book->title }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('book.stream', $book) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <x-icon name="fas-up-right-from-square" class="w-4 h-4" />
                    {{ __('Buka di Tab Baru') }}
                </a>
            </div>
        </div>

        <!-- PDF Viewer -->
        <div class="flex-1 bg-gray-900 rounded-2xl overflow-hidden shadow-xl">
            <iframe src="{{ route('book.stream', $book) }}" class="w-full h-full" frameborder="0"
                title="{{ $book->title }}"></iframe>
        </div>
    </div>
</x-layouts.app>
