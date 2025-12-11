<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Katalog Buku') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Jelajahi koleksi buku perpustakaan') }}</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <x-icon name="fas-search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('Cari judul, penulis...') }}"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
            <select name="category"
                class="sm:w-48 px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Semua Kategori') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors">
                {{ __('Cari') }}
            </button>
        </form>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-6">
        @forelse($books as $book)
            <a href="{{ route(auth()->user()->role . '.catalog.show', $book) }}" class="group">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all">
                    <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 relative">
                        @if ($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <x-icon name="fas-book" class="w-12 h-12 text-gray-400" />
                            </div>
                        @endif
                        <div class="absolute bottom-2 right-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium {{ $book->available_copies > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $book->available_copies > 0 ? __('Tersedia') : __('Habis') }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm line-clamp-2 mb-1">
                            {{ $book->title }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">{{ $book->author }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-12 text-center">
                <x-icon name="fas-book" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Tidak ada buku ditemukan') }}</p>
            </div>
        @endforelse
    </div>

    {{ $books->links() }}
</x-layouts.app>
