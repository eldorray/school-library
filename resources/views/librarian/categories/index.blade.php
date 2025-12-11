<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Kelola Kategori') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kategori untuk klasifikasi buku') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Tambah Kategori') }}</h2>
                <form method="POST" action="{{ route('librarian.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Nama Kategori') }} *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Deskripsi') }}</label>
                        <textarea id="description" name="description" rows="2"
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" 
                            class="w-full px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg">
                        {{ __('Tambah') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" x-data="{ editing: false }">
                            <div x-show="!editing" class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->description ?? '-' }}</p>
                                    <span class="text-xs text-gray-400">{{ $category->books_count }} {{ __('buku') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="editing = true" class="p-2 text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <x-icon name="fas-pen" class="w-4 h-4" />
                                    </button>
                                    @if($category->books_count === 0)
                                        <form method="POST" action="{{ route('librarian.categories.destroy', $category) }}" class="inline"
                                              onsubmit="return confirm('{{ __('Hapus kategori ini?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                <x-icon name="fas-trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <form x-show="editing" method="POST" action="{{ route('librarian.categories.update', $category) }}" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ $category->description }}</textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">
                                        {{ __('Simpan') }}
                                    </button>
                                    <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        {{ __('Batal') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <x-icon name="fas-tags" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                            <p>{{ __('Belum ada kategori') }}</p>
                        </div>
                    @endforelse
                </div>
                @if($categories->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
