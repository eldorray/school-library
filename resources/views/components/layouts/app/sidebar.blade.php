            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            {{-- Admin Menu --}}
                            @if(auth()->user()->isAdmin())
                                <x-layouts.sidebar-link href="{{ route('admin.dashboard') }}" icon='fas-gauge-high'
                                    :active="request()->routeIs('admin.dashboard')">Dashboard</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('admin.users.index') }}" icon='fas-users'
                                    :active="request()->routeIs('admin.users.*')">Kelola User</x-layouts.sidebar-link>

                                <x-layouts.sidebar-two-level-link-parent title="Perpustakaan" icon="fas-book"
                                    :active="request()->routeIs('librarian.*')">
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.dashboard') }}" icon='fas-chart-line'
                                        :active="request()->routeIs('librarian.dashboard')">Dashboard</x-layouts.sidebar-two-level-link>
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.books.index') }}" icon='fas-book-open'
                                        :active="request()->routeIs('librarian.books.*')">Buku</x-layouts.sidebar-two-level-link>
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.categories.index') }}" icon='fas-tags'
                                        :active="request()->routeIs('librarian.categories.*')">Kategori</x-layouts.sidebar-two-level-link>
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.members.index') }}" icon='fas-id-card'
                                        :active="request()->routeIs('librarian.members.*')">Anggota</x-layouts.sidebar-two-level-link>
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.borrowings.index') }}" icon='fas-hand-holding'
                                        :active="request()->routeIs('librarian.borrowings.*')">Peminjaman</x-layouts.sidebar-two-level-link>
                                    <x-layouts.sidebar-two-level-link href="{{ route('librarian.fines.index') }}" icon='fas-money-bill'
                                        :active="request()->routeIs('librarian.fines.*')">Denda</x-layouts.sidebar-two-level-link>
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            {{-- Librarian Menu --}}
                            @if(auth()->user()->isLibrarian())
                                <x-layouts.sidebar-link href="{{ route('librarian.dashboard') }}" icon='fas-gauge-high'
                                    :active="request()->routeIs('librarian.dashboard')">Dashboard</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('librarian.books.index') }}" icon='fas-book-open'
                                    :active="request()->routeIs('librarian.books.*')">Kelola Buku</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('librarian.categories.index') }}" icon='fas-tags'
                                    :active="request()->routeIs('librarian.categories.*')">Kategori</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('librarian.members.index') }}" icon='fas-id-card'
                                    :active="request()->routeIs('librarian.members.*')">Anggota</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('librarian.borrowings.index') }}" icon='fas-hand-holding'
                                    :active="request()->routeIs('librarian.borrowings.*')">Peminjaman</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('librarian.fines.index') }}" icon='fas-money-bill'
                                    :active="request()->routeIs('librarian.fines.*')">Denda</x-layouts.sidebar-link>
                            @endif

                            {{-- Teacher Menu --}}
                            @if(auth()->user()->isTeacher())
                                <x-layouts.sidebar-link href="{{ route('teacher.dashboard') }}" icon='fas-gauge-high'
                                    :active="request()->routeIs('teacher.dashboard')">Dashboard</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('teacher.catalog.index') }}" icon='fas-book-open'
                                    :active="request()->routeIs('teacher.catalog.*')">Katalog Buku</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('teacher.borrowings.index') }}" icon='fas-history'
                                    :active="request()->routeIs('teacher.borrowings.*')">Riwayat Peminjaman</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('teacher.reservations.index') }}" icon='fas-bookmark'
                                    :active="request()->routeIs('teacher.reservations.*')">Reservasi Saya</x-layouts.sidebar-link>
                            @endif

                            {{-- Student Menu --}}
                            @if(auth()->user()->isStudent())
                                <x-layouts.sidebar-link href="{{ route('student.dashboard') }}" icon='fas-gauge-high'
                                    :active="request()->routeIs('student.dashboard')">Dashboard</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('student.catalog.index') }}" icon='fas-book-open'
                                    :active="request()->routeIs('student.catalog.*')">Katalog Buku</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('student.borrowings.index') }}" icon='fas-history'
                                    :active="request()->routeIs('student.borrowings.*')">Riwayat Peminjaman</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('student.reservations.index') }}" icon='fas-bookmark'
                                    :active="request()->routeIs('student.reservations.*')">Reservasi Saya</x-layouts.sidebar-link>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
