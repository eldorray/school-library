<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.borrowings.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali') }}
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Peminjaman Baru') }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('librarian.borrowings.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Anggota') }} *</label>
                    <select id="member_id" name="member_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('member_id') border-red-500 @enderror">
                        <option value="">{{ __('Pilih Anggota') }}</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->member_id }} - {{ $member->user->name }} ({{ ucfirst($member->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="book_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Buku') }} *</label>
                    <select id="book_id" name="book_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('book_id') border-red-500 @enderror">
                        <option value="">{{ __('Pilih Buku') }}</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} (tersedia: {{ $book->available_copies }})
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Tanggal Jatuh Tempo') }} *</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('due_date') border-red-500 @enderror">
                    @error('due_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Catatan') }}</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg">
                        {{ __('Proses Peminjaman') }}
                    </button>
                    <a href="{{ route('librarian.borrowings.index') }}" 
                       class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
