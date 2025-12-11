<x-layouts.app>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Kelola Buku') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola koleksi buku perpustakaan') }}</p>
        </div>
        <a href="{{ route('librarian.books.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <x-icon name="fas-plus" class="w-4 h-4" />
            {{ __('Tambah Buku') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <x-icon name="fas-search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Cari judul, penulis, atau ISBN...') }}"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                </div>
            </div>
            <div class="sm:w-48">
                <select name="category"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    <option value="">{{ __('Semua Kategori') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Books Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Cover') }}
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Nama Buku') }}
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Ketersediaan') }}
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Aksi') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <!-- Cover -->
                            <td class="px-6 py-4">
                                <div
                                    class="w-16 h-20 rounded-lg overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex-shrink-0">
                                    @if ($book->cover_image)
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-icon name="fas-book" class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Nama Buku -->
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $book->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                                    <span
                                        class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                        {{ $book->category->name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Ketersediaan -->
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $book->available_copies }}/{{ $book->total_copies }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Baca Buku -->
                                    @if ($book->pdf_file)
                                        <a href="{{ route('book.read', $book) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-100 from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm"
                                            title="{{ __('Baca Buku') }}">
                                            <x-icon name="fas-book-open" class="w-4 h-4" />
                                            {{ __('Baca') }}
                                        </a>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed"
                                            title="{{ __('PDF tidak tersedia') }}">
                                            <x-icon name="fas-book-open" class="w-4 h-4" />
                                            {{ __('Baca') }}
                                        </span>
                                    @endif

                                    <!-- Detail -->
                                    <a href="{{ route('librarian.books.show', $book) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                        title="{{ __('Detail') }}">
                                        <x-icon name="fas-eye" class="w-4 h-4" />
                                        {{ __('Detail') }}
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('librarian.books.edit', $book) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900 transition-colors"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="fas-pen" class="w-4 h-4" />
                                        {{ __('Edit') }}
                                    </a>

                                    <!-- Hapus -->
                                    <form action="{{ route('librarian.books.destroy', $book) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('{{ __('Yakin ingin menghapus buku ini?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/50 rounded-lg hover:bg-red-200 dark:hover:bg-red-900 transition-colors"
                                            title="{{ __('Hapus') }}">
                                            <x-icon name="fas-trash" class="w-4 h-4" />
                                            {{ __('Hapus') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <x-icon name="fas-book" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                                <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Belum ada buku') }}</p>
                                <a href="{{ route('librarian.books.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 text-emerald-600 dark:text-emerald-400 hover:underline">
                                    <x-icon name="fas-plus" class="w-4 h-4" />
                                    {{ __('Tambah buku pertama') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    {{ $books->links() }}
</x-layouts.app>
