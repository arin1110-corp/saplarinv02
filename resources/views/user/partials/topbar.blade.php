<header class="sticky top-0 z-30 bg-[#F5F7FB]/90 backdrop-blur border-b border-slate-200">

    <div class="px-5 lg:px-8 py-5 flex items-center justify-between">

        <div class="flex items-center gap-3">

            {{-- Tombol Sidebar Mobile --}}
            <button id="btnSidebar"
                class="lg:hidden w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-sm flex items-center justify-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-slate-700"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />

                </svg>

            </button>

            <div>
                <p class="text-sm text-slate-500">
                    SAPLARIN / @yield('breadcrumb', 'Dashboard')
                </p>

                <h1 class="text-2xl font-extrabold text-slate-900">
                    @yield('page_title', 'Dashboard')
                </h1>
            </div>

        </div>

        <div
            class="hidden sm:flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm">

            <div
                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700">

                {{ strtoupper(substr(session('pegawai_nama', 'U'), 0, 1)) }}

            </div>

            <div class="text-right">
                <p class="font-semibold text-slate-800 leading-tight">
                    {{ session('pegawai_nama') ?? 'User' }}
                </p>

                <p class="text-xs text-slate-500">
                    {{ session('pegawai_nip') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

</header>