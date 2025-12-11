<x-layouts.app>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Kelola Anggota') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Daftar anggota perpustakaan') }}</p>
        </div>
        <a href="{{ route('librarian.members.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-medium hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg">
            <x-icon name="fas-plus" class="w-4 h-4" />
            {{ __('Daftarkan Anggota') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <x-icon name="fas-search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('Cari nama atau NIS/NIP...') }}"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            </div>
            <select name="type"
                class="sm:w-40 px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Semua Tipe') }}</option>
                <option value="student" {{ request('type') === 'student' ? 'selected' : '' }}>{{ __('Siswa') }}
                </option>
                <option value="teacher" {{ request('type') === 'teacher' ? 'selected' : '' }}>{{ __('Guru') }}
                </option>
            </select>
            <button type="submit"
                class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Anggota') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('ID/NIS') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Tipe') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Kelas/Jurusan') }}</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Status') }}</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            {{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full {{ $member->type === 'teacher' ? 'bg-green-100 dark:bg-green-900' : 'bg-amber-100 dark:bg-amber-900' }} flex items-center justify-center text-sm font-medium {{ $member->type === 'teacher' ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ substr($member->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $member->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300 font-mono">{{ $member->member_id }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $member->type === 'teacher' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' }}">
                                    {{ $member->type === 'teacher' ? __('Guru') : __('Siswa') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $member->class ?? ($member->department ?? '-') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $member->is_active ? __('Aktif') : __('Nonaktif') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('librarian.members.show', $member) }}"
                                        class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <x-icon name="fas-eye" class="w-4 h-4" />
                                    </a>
                                    <a href="{{ route('librarian.members.edit', $member) }}"
                                        class="p-2 text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                        <x-icon name="fas-pen" class="w-4 h-4" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-icon name="fas-id-card" class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Belum ada anggota terdaftar') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($members->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
