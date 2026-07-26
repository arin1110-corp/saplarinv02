<aside
    x-cloak
    :class="{
        '-translate-x-full': isMobile && !sidebarOpen,
        'translate-x-0': !isMobile || sidebarOpen
    }"
    class="fixed inset-y-0 left-0 z-50 w-64 lg:w-72 bg-white dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 shadow-xl transition-transform duration-300 flex flex-col">
    
    {{-- Header --}}
    <div class="h-20 shrink-0 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800">

        <div class="flex items-center gap-3">

            <div
                class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow">

                <i class="bi bi-grid text-lg"></i>

            </div>

            <div>

                <h1 class="text-xl font-bold text-slate-900 dark:text-white">

                    SAPLARIN

                </h1>

                <p class="text-xs text-slate-500">

                    Administrator

                </p>

            </div>

        </div>

        <button
            @click="closeSidebar()"
            class="lg:hidden w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>

    {{-- Search --}}
    <div class="p-5 border-b border-slate-200 dark:border-slate-800">

        <div class="relative">

            <i
                class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">

            </i>

            <input
                type="text"
                placeholder="Cari menu..."
                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

        </div>

    </div>

    {{-- Menu --}}
    <div class="flex-1 overflow-y-auto">

        <nav class="p-5 space-y-6">

            {{-- MAIN --}}
            <div>

                <p class="text-xs uppercase tracking-widest text-slate-400 mb-3">

                    MAIN MENU

                </p>

                <a
                    href="{{ route('administrator.dashboard') }}"
                    class="{{ request()->routeIs('administrator.dashboard')
                        ? 'bg-blue-600 text-white shadow-md'
                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                    flex items-center gap-3 rounded-xl px-4 py-3 transition">

                    <i class="bi bi-grid"></i>

                    <span>

                        Dashboard

                    </span>

                </a>

            </div>

            {{-- MASTER DATA --}}
            <div x-data="{open:true}">

                <button
                    @click="open=!open"
                    class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <div class="flex items-center gap-3">

                        <i class="bi bi-folder2-open"></i>

                        <span>

                            Master Data

                        </span>

                    </div>

                    <i
                        class="bi transition"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                    </i>

                </button>

                <div
                    x-show="open"
                    x-transition
                    class="mt-2 space-y-1">
                                        <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-people"></i>

                        <span>User</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-building"></i>

                        <span>Bidang</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-diagram-3"></i>

                        <span>Program</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-kanban"></i>

                        <span>Kegiatan</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-bank"></i>

                        <span>Rekening</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-person-vcard"></i>

                        <span>Pegawai</span>

                    </a>

                </div>

            </div>

            {{-- Administrasi --}}
            <div x-data="{open:true}">

                <button
                    @click="open=!open"
                    class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <div class="flex items-center gap-3">

                        <i class="bi bi-folder-check"></i>

                        <span>Administrasi</span>

                    </div>

                    <i
                        class="bi transition"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                    </i>

                </button>

                <div
                    x-show="open"
                    x-transition
                    class="mt-2 space-y-1">

                    <a
                        href="#"
                        class="flex items-center justify-between rounded-lg px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-receipt"></i>

                            <span>SPJ</span>

                        </div>

                        <span
                            class="px-2 py-0.5 rounded-full bg-blue-600 text-white text-xs">

                            12

                        </span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-fuel-pump"></i>

                        <span>BBM</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-file-earmark-text"></i>

                        <span>Laporan</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-lg px-5 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-clipboard-data"></i>

                        <span>Monitoring</span>

                    </a>

                </div>

            </div>
                        {{-- SYSTEM --}}
            <div>

                <p class="text-xs uppercase tracking-widest text-slate-400 mb-3">

                    SYSTEM

                </p>

                <div class="space-y-1">

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-gear"></i>

                        <span>Pengaturan</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-shield-lock"></i>

                        <span>Hak Akses</span>

                    </a>

                    <a
                        href="#"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <i class="bi bi-clock-history"></i>

                        <span>Log Aktivitas</span>

                    </a>

                </div>

            </div>

        </nav>

    </div>

    {{-- Footer --}}
    <div class="border-t border-slate-200 dark:border-slate-800 p-5">

        <div class="flex items-center gap-3 mb-4">

            <div
                class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-semibold">

                A

            </div>

            <div class="flex-1 min-w-0">

                <div class="font-medium text-slate-900 dark:text-white truncate">

                    Administrator

                </div>

                <div class="text-xs text-slate-500 truncate">

                    admin@saplarin.id

                </div>

            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 text-white py-3 transition">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>