<!-- SIDEBAR -->
<aside class="w-64 bg-slate-900 border-r border-slate-800 hidden md:block">

    <div class="p-6 border-b border-slate-800">

        <!-- LOGO + TITLE -->
        <div class="flex items-center gap-3">

            <img src="{{ asset('image/pemprov.png') }}"
                alt="Logo"
                class="w-12 h-12 object-contain">

            <div>
                <h1 class="text-2xl font-bold tracking-wide leading-tight">
                    SAPLAR<span class="text-blue-400">IN</span>
                </h1>

                <p class="text-xs text-slate-400">
                    Sistem Penataan Laporan Internal
                </p>
            </div>

        </div>

        <!-- NAMA DINAS -->
        <div class="mt-4 pt-4 border-t border-slate-800">

            <p class="text-[11px] uppercase tracking-[2px] text-slate-500 leading-relaxed text-center">
                Dinas Kebudayaan <br>
                Provinsi Bali
            </p>

        </div>

        <!-- ROLE BUTTON -->
        <div class="mt-4 flex justify-center">

            <button onclick="openRoleModal()"
                class="bg-slate-800 hover:bg-slate-700 border border-slate-700 px-4 py-2 rounded-xl transition duration-300 text-sm text-slate-200 shadow-lg">

                {{ session('active_role') }}
            </button>

        </div>

    </div>

    <!-- MENU -->
    <nav class="mt-6 px-4 text-sm flex flex-col gap-2">

        <!-- DASHBOARD -->
        <a href="{{ route('admin.dashboard') }}"
            class="block px-4 py-2 rounded-lg transition duration-200
            {{ request()->routeIs('admin.dashboard')
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-slate-800 text-slate-300' }}">

            Dashboard
        </a>

        <!-- DATA USER -->
        <a href="{{ route('admin.users') }}"
            class="block px-4 py-2 rounded-lg transition duration-200
            {{ request()->routeIs('admin.users')
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-slate-800 text-slate-300' }}">

            Data User
        </a>

        <!-- DATA MASTER -->
        <div>

            <button onclick="toggleMasterMenu()"
                class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition duration-200
                {{ request()->routeIs('admin.program') ||
                request()->routeIs('admin.kegiatan') ||
                request()->routeIs('admin.subkegiatan')
                    ? 'bg-blue-600 text-white shadow-lg'
                    : 'hover:bg-slate-800 text-slate-300' }}">

                <span>Data Master</span>

                <svg id="masterArrow"
                    class="w-4 h-4 transition-transform duration-300
                    {{ request()->routeIs('admin.program') ||
                    request()->routeIs('admin.kegiatan') ||
                    request()->routeIs('admin.subkegiatan')
                        ? 'rotate-180'
                        : '' }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>

            </button>

            <div id="masterMenu"
                class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300
                {{ request()->routeIs('admin.program') ||
                request()->routeIs('admin.kegiatan') ||
                request()->routeIs('admin.subkegiatan')
                    ? 'max-h-40'
                    : 'max-h-0' }}">

                <a href="{{ route('admin.program') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.program')
                        ? 'bg-slate-700 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Program
                </a>

                <a href="{{ route('admin.kegiatan') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.kegiatan')
                        ? 'bg-slate-700 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Kegiatan
                </a>

                <a href="{{ route('admin.subkegiatan') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition
                    {{ request()->routeIs('admin.subkegiatan')
                        ? 'bg-slate-700 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    Sub Kegiatan
                </a>

            </div>
        </div>

        <!-- INVENTARIS -->
        <a href="#"
            class="block px-4 py-2 rounded-lg transition duration-200
            {{ request()->routeIs('admin.inventaris')
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-slate-800 text-slate-300' }}">

            Inventaris
        </a>

        <!-- DATA PERMINTAAN -->
        <div>

            <button onclick="togglePermintaanMenu()"
                class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition duration-200
                {{ request()->routeIs('admin.permintaan*')
                    ? 'bg-blue-600 text-white shadow-lg'
                    : 'hover:bg-slate-800 text-slate-300' }}">

                <span>Data Permintaan</span>

                <svg id="permintaanArrow"
                    class="w-4 h-4 transition-transform duration-300
                    {{ request()->routeIs('admin.permintaan*')
                        ? 'rotate-180'
                        : '' }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>

            </button>

            <div id="permintaanMenu"
                class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300
                {{ request()->routeIs('admin.permintaan*')
                    ? 'max-h-40'
                    : 'max-h-0' }}">

                <a href="{{ route('admin.permintaan.spj') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">

                    Permintaan SPJ
                </a>

                <a href="{{ route('admin.permintaan.kak') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">

                    Permintaan KAK
                </a>

            </div>
        </div>

        <!-- LAPORAN -->
        <div>

            <button onclick="toggleLaporanMenu()"
                class="w-full flex justify-between items-center px-4 py-2 rounded-lg transition duration-200
                {{ request()->routeIs('admin.laporan*')
                    ? 'bg-blue-600 text-white shadow-lg'
                    : 'hover:bg-slate-800 text-slate-300' }}">

                <span>Laporan</span>

                <svg id="laporanArrow"
                    class="w-4 h-4 transition-transform duration-300
                    {{ request()->routeIs('admin.laporan*')
                        ? 'rotate-180'
                        : '' }}"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>

            </button>

            <div id="laporanMenu"
                class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300
                {{ request()->routeIs('admin.laporan*')
                    ? 'max-h-96'
                    : 'max-h-0' }}">

                <a href="{{ route('admin.laporan.spj') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">
                    Laporan SPJ
                </a>

                <a href="{{ route('admin.laporan.kegiatan') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">
                    Laporan Kegiatan
                </a>

                <a href="{{ route('admin.laporan.subkegiatan') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">
                    Laporan Sub Kegiatan
                </a>

                <a href="{{ route('admin.laporan.pwa') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">
                    Laporan PWA
                </a>

                <a href="{{ route('admin.laporan.kak') }}"
                    class="block px-4 py-2 rounded-lg text-sm transition text-slate-400 hover:bg-slate-800 hover:text-white">
                    Laporan KAK
                </a>

            </div>
        </div>

    </nav>
</aside>

<script>
    function toggleMasterMenu() {
        document.getElementById('masterMenu').classList.toggle('max-h-0');
        document.getElementById('masterMenu').classList.toggle('max-h-40');
        document.getElementById('masterArrow').classList.toggle('rotate-180');
    }

    function togglePermintaanMenu() {
        document.getElementById('permintaanMenu').classList.toggle('max-h-0');
        document.getElementById('permintaanMenu').classList.toggle('max-h-40');
        document.getElementById('permintaanArrow').classList.toggle('rotate-180');
    }

    function toggleLaporanMenu() {
        document.getElementById('laporanMenu').classList.toggle('max-h-0');
        document.getElementById('laporanMenu').classList.toggle('max-h-96');
        document.getElementById('laporanArrow').classList.toggle('rotate-180');
    }
</script>