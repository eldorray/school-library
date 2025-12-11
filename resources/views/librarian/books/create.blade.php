<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.books.index') }}"
            class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali ke Daftar Buku') }}
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Buku Baru') }}</h1>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('librarian.books.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Judul Buku') }}
                            *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="author"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Penulis') }}
                            *</label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('author') border-red-500 @enderror">
                        @error('author')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="isbn"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ISBN') }}</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('isbn') border-red-500 @enderror">
                        @error('isbn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Kategori') }}
                            *</label>
                        <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('category_id') border-red-500 @enderror">
                            <option value="">{{ __('Pilih Kategori') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="publisher"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Penerbit') }}</label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label for="publish_year"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Tahun Terbit') }}</label>
                        <input type="number" id="publish_year" name="publish_year" value="{{ old('publish_year') }}"
                            min="1800" max="{{ date('Y') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label for="total_copies"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Jumlah Eksemplar') }}
                            *</label>
                        <input type="number" id="total_copies" name="total_copies"
                            value="{{ old('total_copies', 1) }}" min="1" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('total_copies') border-red-500 @enderror">
                        @error('total_copies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Deskripsi') }}</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cover_image"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Cover Buku') }}</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition-all">
                        @error('cover_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="pdf_file"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('File PDF Buku') }}</label>
                        <input type="file" id="pdf_file" name="pdf_file" accept=".pdf"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 transition-all">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Format: PDF, Maks. 50MB. Untuk dibaca online.') }}</p>
                        @error('pdf_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        {{ __('Simpan') }}
                    </button>
                    <a href="{{ route('librarian.books.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
