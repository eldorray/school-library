<x-layouts.app>
    <div class="mb-6">
        <a href="{{ route('librarian.members.index') }}"
            class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 mb-4">
            <x-icon name="fas-arrow-left" class="w-4 h-4 mr-2" />
            {{ __('Kembali') }}
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Anggota') }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('librarian.members.update', $member) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Nama Lengkap') }}
                            *</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $member->user->name) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Email') }}
                            *</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $member->user->email) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="member_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('NIS/NIP') }}
                            *</label>
                        <input type="text" id="member_id" name="member_id"
                            value="{{ old('member_id', $member->member_id) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('member_id') border-red-500 @enderror">
                        @error('member_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($member->type === 'student')
                        <div>
                            <label for="class"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Kelas') }}</label>
                            <input type="text" id="class" name="class"
                                value="{{ old('class', $member->class) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        </div>
                    @else
                        <div>
                            <label for="department"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Mata Pelajaran') }}</label>
                            <input type="text" id="department" name="department"
                                value="{{ old('department', $member->department) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        </div>
                    @endif

                    <div>
                        <label for="phone"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Telepon') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Alamat') }}</label>
                        <textarea id="address" name="address" rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $member->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-amber-600 focus:ring-amber-500">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Anggota Aktif') }}</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ __('Kosongkan password jika tidak ingin mengubah') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Password Baru') }}</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Konfirmasi Password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-medium hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg">
                        {{ __('Perbarui') }}
                    </button>
                    <a href="{{ route('librarian.members.show', $member) }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
