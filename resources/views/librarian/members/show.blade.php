<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.members.index') }}"
            class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali') }}
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Anggota') }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Member Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto rounded-full {{ $member->type === 'teacher' ? 'bg-green-100 dark:bg-green-900' : 'bg-amber-100 dark:bg-amber-900' }} flex items-center justify-center text-2xl font-bold {{ $member->type === 'teacher' ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $member->user->initials() }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-4">{{ $member->user->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ $member->member_id }}</p>
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium mt-2 {{ $member->type === 'teacher' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' }}">
                        {{ $member->type === 'teacher' ? __('Guru') : __('Siswa') }}
                    </span>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ $member->user->email }}</span>
                    </div>
                    @if ($member->phone)
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Telepon</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $member->phone }}</span>
                        </div>
                    @endif
                    @if ($member->class)
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Kelas</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $member->class }}</span>
                        </div>
                    @endif
                    @if ($member->department)
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Mata Pelajaran</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $member->department }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Status</span>
                        <span class="{{ $member->is_active ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $member->is_active ? __('Aktif') : __('Nonaktif') }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-500">Terdaftar</span>
                        <span
                            class="text-gray-900 dark:text-gray-100">{{ $member->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('librarian.members.edit', $member) }}"
                        class="w-full block text-center px-6 py-2.5 bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300 rounded-xl font-medium hover:bg-amber-200 dark:hover:bg-amber-800 transition-colors">
                        {{ __('Edit Anggota') }}
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mt-6">
                <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">{{ __('Statistik') }}</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $member->borrowings()->where('status', 'borrowed')->count() }}</p>
                        <p class="text-xs text-gray-500">{{ __('Dipinjam') }}</p>
                    </div>
                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $member->borrowings()->count() }}</p>
                        <p class="text-xs text-gray-500">{{ __('Total Pinjam') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing History -->
        <div class="lg:col-span-2">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ __('Riwayat Peminjaman') }}</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($member->borrowings()->with(['book', 'fine'])->latest()->take(10)->get() as $borrowing)
                        <div
                            class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div
                                class="w-12 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if ($borrowing->book->cover_image)
                                    <img src="{{ Storage::url($borrowing->book->cover_image) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <x-icon name="fas-book" class="w-5 h-5 text-gray-400" />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $borrowing->book->title }}</p>
                                <p class="text-sm text-gray-500">{{ $borrowing->borrow_date->format('d M Y') }}
                                    @if ($borrowing->return_date)
                                        → {{ $borrowing->return_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if ($borrowing->status === 'returned') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($borrowing->isOverdue()) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                    @if ($borrowing->status === 'returned')
                                        {{ __('Dikembalikan') }}
                                    @elseif($borrowing->isOverdue())
                                        {{ __('Terlambat') }}
                                    @else
                                        {{ __('Dipinjam') }}
                                    @endif
                                </span>
                                @if ($borrowing->fine)
                                    <span
                                        class="text-xs {{ $borrowing->fine->is_paid ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $borrowing->fine->formattedAmount() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <x-icon name="fas-book-open" class="w-10 h-10 mx-auto mb-2 opacity-50" />
                            <p>{{ __('Belum ada riwayat peminjaman') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
