<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('librarian.books.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <x-icon name="fas-arrow-left" class="w-4 h-4" />
                {{ __('Kembali ke Daftar Buku') }}
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ __('Import Buku dari Excel') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Upload file Excel untuk menambahkan banyak buku sekaligus') }}</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Success / Error Messages --}}
                @if (session('status'))
                    <div
                        class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                        <div class="flex items-center gap-3">
                            <x-icon name="fas-check-circle" class="w-5 h-5 text-green-600 dark:text-green-400" />
                            <p class="text-green-700 dark:text-green-300">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('import_errors'))
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        <div class="flex items-start gap-3">
                            <x-icon name="fas-exclamation-triangle"
                                class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="font-medium text-red-700 dark:text-red-300 mb-2">
                                    {{ __('Beberapa baris gagal diimport:') }}</p>
                                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                                    @foreach (session('import_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @error('file')
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        <div class="flex items-center gap-3">
                            <x-icon name="fas-exclamation-circle" class="w-5 h-5 text-red-600 dark:text-red-400" />
                            <p class="text-red-700 dark:text-red-300">{{ $message }}</p>
                        </div>
                    </div>
                @enderror

                {{-- Download Template --}}
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-icon name="fas-file-excel" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                            <div>
                                <p class="font-medium text-blue-900 dark:text-blue-100">{{ __('Template Import') }}</p>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    {{ __('Download template untuk format yang benar') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('librarian.books.import.template') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <x-icon name="fas-download" class="w-4 h-4" />
                            {{ __('Download') }}
                        </a>
                    </div>
                </div>

                {{-- Upload Form --}}
                <form action="{{ route('librarian.books.import.store') }}" method="POST" enctype="multipart/form-data"
                    x-data="{ fileName: '' }">
                    @csrf

                    <div
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors">
                        <input type="file" name="file" id="file" accept=".xlsx,.xls" class="hidden"
                            @change="fileName = $event.target.files[0]?.name || ''">

                        <label for="file" class="cursor-pointer">
                            <x-icon name="fas-cloud-upload-alt"
                                class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                            <p class="text-gray-700 dark:text-gray-300 font-medium mb-1" x-show="!fileName">
                                {{ __('Klik untuk pilih file') }}</p>
                            <p class="text-gray-700 dark:text-gray-300 font-medium mb-1" x-show="fileName"
                                x-text="fileName"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Format: .xlsx atau .xls (Maks. 10MB)') }}</p>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('librarian.books.index') }}"
                            class="px-6 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl">
                            <x-icon name="fas-upload" class="w-4 h-4 inline-block mr-2" />
                            {{ __('Import Buku') }}
                        </button>
                    </div>
                </form>

                {{-- Format Guide --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Format Kolom Excel') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Kolom') }}</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Wajib') }}</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Keterangan') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">judul</td>
                                    <td class="px-4 py-2"><span class="text-red-600 dark:text-red-400">Ya</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('Judul buku') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">penulis</td>
                                    <td class="px-4 py-2"><span class="text-red-600 dark:text-red-400">Ya</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('Nama penulis') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">kategori</td>
                                    <td class="px-4 py-2"><span class="text-red-600 dark:text-red-400">Ya</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">
                                        {{ __('Nama kategori (harus sudah ada di sistem)') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">isbn</td>
                                    <td class="px-4 py-2"><span class="text-gray-500">Tidak</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('Nomor ISBN') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">penerbit</td>
                                    <td class="px-4 py-2"><span class="text-gray-500">Tidak</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('Nama penerbit') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">tahun_terbit</td>
                                    <td class="px-4 py-2"><span class="text-gray-500">Tidak</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">
                                        {{ __('Tahun terbit (angka 4 digit)') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">deskripsi</td>
                                    <td class="px-4 py-2"><span class="text-gray-500">Tidak</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('Deskripsi buku') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-gray-900 dark:text-gray-100">jumlah_eksemplar
                                    </td>
                                    <td class="px-4 py-2"><span class="text-gray-500">Tidak</span></td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400">
                                        {{ __('Jumlah eksemplar (default: 1)') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Available Categories --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Kategori yang Tersedia') }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($categories as $category)
                            <span
                                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
