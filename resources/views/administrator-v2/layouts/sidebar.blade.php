@php
    $role = session('active_role');

    $isAdminFull = $role === 'Admin Full';
    $isAdminArsiparis = $role === 'Admin Arsiparis';
    $isAdminBBM = $role === 'Admin BBM';

    $canUser = $isAdminFull;
    $canDrive = $isAdminFull || $isAdminArsiparis;
    $canMaster = $isAdminFull;

    $canPermintaanBBM = $isAdminFull || $isAdminBBM;
    $canPermintaanSPJ = $isAdminFull || $isAdminArsiparis;
    $canPermintaanKAK = $isAdminFull || $isAdminArsiparis;
    $canPermintaanSubKegiatan = $isAdminFull || $isAdminArsiparis;
    $canPermintaanSHS = $isAdminFull || $isAdminArsiparis;

    $canDataPermintaan =
        $canPermintaanBBM || $canPermintaanSPJ || $canPermintaanKAK || $canPermintaanSubKegiatan || $canPermintaanSHS;

    $canKinerjaPrioritas = $isAdminFull || $isAdminArsiparis;
    $canLaporanAktivitas = $isAdminFull || $isAdminArsiparis;
    $canDataPaguSPJ = $isAdminFull || $isAdminArsiparis;
    $canLaporanSPJ = $isAdminFull || $isAdminArsiparis;
    $canLaporanSubKegiatan = $isAdminFull || $isAdminArsiparis;
    $canLaporanSHS = $isAdminFull || $isAdminArsiparis;
    $canLaporanKAK = $isAdminFull || $isAdminArsiparis;

    $canDataLaporan =
        $canKinerjaPrioritas ||
        $canLaporanAktivitas ||
        $canDataPaguSPJ ||
        $canLaporanSPJ ||
        $canLaporanSubKegiatan ||
        $canLaporanSHS ||
        $canLaporanKAK;
@endphp


<aside x-cloak
    :class="{
        '-translate-x-full': isMobile && !sidebarOpen,
        'translate-x-0': isMobile ? sidebarOpen : true,
        'lg:w-20': sidebarMini,
        'lg:w-72': !sidebarMini
    }"
    class="fixed inset-y-0 left-0 z-50
           bg-white dark:bg-slate-950
           border-r border-slate-200 dark:border-slate-800
           shadow-xl transition-all duration-300 flex flex-col">
    {{-- Header --}}
    <div class="h-20 shrink-0 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800">

        <div class="flex items-center gap-3">

            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center shadow">

                <img src="{{ asset('image/pemprov.png') }}" alt="Pemprov Bali" class="w-9 h-9 object-contain">

            </div>

            <div>

                <h2 class="font-bold text-xl dark:text-white">

                    SAPLAR<span class="text-blue-600">IN</span>

                </h2>

                <p class="text-xs text-slate-500">

                    Administrator

                </p>

            </div>

        </div>

        <button @click="closeSidebar()"
            class="lg:hidden w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>

    {{-- Ganti Role --}}
    <div class="p-5 border-b border-slate-200 dark:border-slate-800">

        <button type="button" onclick="openRoleModal()"
            class="w-full flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center">

                    <i class="bi bi-person-gear"></i>

                </div>

                <div class="text-left">

                    <div class="text-sm font-semibold text-slate-800 dark:text-white">

                        {{ session('active_role') }}

                    </div>

                    <div class="text-xs text-slate-500">

                        Ganti Role

                    </div>

                </div>

            </div>

            <i class="bi bi-chevron-right text-slate-400"></i>

        </button>

    </div>

    {{-- Menu --}}
    <div class="flex-1 overflow-y-auto">

        <nav class="p-5 space-y-6">

            {{-- Dashboard --}}
            <div>

                <p class="text-xs uppercase tracking-widest text-slate-400 mb-3">

                    MAIN MENU

                </p>

                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard')
                        ? 'bg-blue-600 text-white shadow'
                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-xl px-4 py-3 transition">

                    <i class="bi bi-grid"></i>

                    <span>Dashboard</span>

                </a>

            </div>

            {{-- User Management --}}
            @if ($canUser)
                <div x-data="{ open: false }">

                    <button type="button" @click="open=!open"
                        class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-people"></i>

                            <span>

                                User Management

                            </span>

                        </div>

                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                        </i>

                    </button>

                    <div x-show="open" x-transition.opacity.duration.200ms class="mt-2 space-y-1">

                        <a href="{{ route('admin.users.index') }}"
                            class="{{ request()->routeIs('admin.users.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5">

                            <i class="bi bi-person"></i>

                            <span>

                                Data User

                            </span>

                        </a>

                    </div>

                </div>
            @endif
            {{-- Drive Management --}}
            @if ($canDrive)
                <div x-data="{ open: false }">

                    <button @click="open=!open"
                        class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-google"></i>

                            <span>

                                Drive Management

                            </span>

                        </div>

                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                        </i>

                    </button>

                    <div x-show="open" x-collapse class="mt-2 space-y-1">

                        <a href="{{ route('admin.drive.json.index') }}"
                            class="{{ request()->routeIs('admin.drive.json.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-key-fill"></i>

                            <span>

                                JSON Credential

                            </span>

                        </a>
                        <a href="{{ route('admin.drive.folder.index') }}"
                            class="{{ request()->routeIs('admin.drive.folder.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-folder2-open"></i>

                            <span>

                                Folder Drive

                            </span>

                        </a>

                    </div>

                </div>
            @endif

            {{-- Master Data --}}
            @if ($canMaster)
                <div x-data="{ open: false }">

                    <button type="button" @click="open=!open"
                        class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-database"></i>

                            <span>

                                Master Data

                            </span>

                        </div>

                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                        </i>

                    </button>

                    <div x-show="open" x-collapse class="mt-2 space-y-1">

                        <a href="{{ route('admin.program.index') }}"
                            class="{{ request()->routeIs('admin.program.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5">

                            <i class="bi bi-diagram-3"></i>

                            <span>Program</span>

                        </a>
                        <a href="{{ route('admin.kegiatan.index') }}"
                            class="{{ request()->routeIs('admin.kegiatan.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }} 
                                    flex items-center gap-3 rounded-lg px-5 py-2.5">

                            <i class="bi bi-kanban"></i>

                            <span>Kegiatan</span>

                        </a>
                        <a href="{{ route('admin.subkegiatan.index') }}"
                            class="{{ request()->routeIs('admin.subkegiatan.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                            flex items-center gap-3 rounded-lg px-5 py-2.5">

                            <i class="bi bi-list-task"></i>

                            <span>Sub Kegiatan</span>

                        </a>

                        @if ($canPermintaanSPJ)
                            <a href="{{ route('admin.spj.index') }}"
                                class="{{ request()->routeIs('admin.spj.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-cash-stack"></i>

                                <span>

                                    Data Pagu SPJ

                                </span>

                            </a>
                        @endif
                        <a href="{{ route('admin.shs-kelompok.index') }}"
                            class="{{ request()->routeIs('admin.shs-kelompok.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-box-seam"></i>

                            <span>

                                Data Kelompok SHS

                            </span>

                        </a>
                        <a href="{{ route('admin.shs-satuan.index') }}"
                            class="{{ request()->routeIs('admin.shs-satuan.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-box2"></i>

                            <span>

                                Data Satuan SHS

                            </span>

                        </a>
                        {{-- Master PAD --}}
                        <div x-data="{ open: false }">

                            <button type="button" @click="open=!open"
                                class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                                <div class="flex items-center gap-3">
                                    <i class="bi bi-cash-coin"></i>
                                    <span>Master PAD</span>
                                </div>

                                <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">
                                </i>

                            </button>

                            <div x-show="open" x-collapse class="mt-2 space-y-1">

                                <a href="{{ route('admin.pad.jenis.index') }}"
                                    class="{{ request()->routeIs('admin.pad.jenis.*')
                                        ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">
                                    <i class="bi bi-tags"></i>
                                    <span>Jenis PAD</span>
                                </a>

                                <a href="{{ route('admin.pad.komponen.index') }}"
                                    class="{{ request()->routeIs('admin.pad.komponen.*')
                                        ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">
                                    <i class="bi bi-list-ul"></i>
                                    <span>Komponen PAD</span>
                                </a>

                                <a href="{{ route('admin.pad.subkomponen.index') }}"
                                    class="{{ request()->routeIs('admin.pad.subkomponen.*')
                                        ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">
                                    <i class="bi bi-list-check"></i>
                                    <span>Subkomponen PAD</span>
                                </a>

                                <a href="{{ route('admin.pad.target.index') }}"
                                    class="{{ request()->routeIs('admin.pad.target.*')
                                        ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">
                                    <i class="bi bi-bullseye"></i>
                                    <span>Target PAD</span>
                                </a>

                            </div>
                        </div>


                    </div>

                </div>
            @endif

            {{-- Data Permintaan --}}
            @if ($canDataPermintaan)
                <div x-data="{ open: false }">

                    <button @click="open=!open"
                        class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-folder-check"></i>

                            <span>

                                Data Permintaan

                            </span>

                        </div>

                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                        </i>

                    </button>

                    <div x-show="open" x-collapse class="mt-2 space-y-1">
                        @if ($canPermintaanBBM)
                            <a href="{{ route('admin.bbm.index') }}"
                                class="{{ request()->routeIs('admin.bbm.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-fuel-pump"></i>

                                <span>

                                    Permintaan BBM

                                </span>

                            </a>
                        @endif
                        @if ($canPermintaanSHS)
                            <a href="{{ route('admin.shs.index') }}"
                                class="{{ request()->routeIs('admin.shs.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-box-seam"></i>

                                <span>

                                    Permintaan SHS

                                </span>

                            </a>
                        @endif
                        @if ($canPermintaanKAK)
                            <a href="{{ route('admin.permintaan.kak') }}"
                                class="{{ request()->routeIs('admin.permintaan.kak.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-file-earmark-text"></i>

                                <span>

                                    Permintaan KAK

                                </span>

                            </a>
                        @endif
                        @if ($canPermintaanSPJ)
                            <a href="{{ route('admin.permintaan.spj.index') }}"
                                class="{{ request()->routeIs('admin.permintaan.spj.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-cash-stack"></i>

                                <span>

                                    Permintaan SPJ

                                </span>

                            </a>
                            <a href="{{ route('admin.pad.permintaan.index') }}"
                                class="{{ request()->routeIs('admin.pad.permintaan.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-cash-coin"></i>

                                <span>
                                    Permintaan PAD
                                </span>

                            </a>
                        @endif
                        @if ($canPermintaanSubKegiatan)
                            <a href="{{ route('admin.sub-kegiatan-indikator.index') }}"
                                class="{{ request()->routeIs('admin.sub-kegiatan-indikator.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-list-task"></i>

                                <span>

                                    Permintaan Sub Kegiatan

                                </span>

                            </a>
                            <a href="{{ route('admin.booking-ruang.dashboard') }}"
                                class="{{ request()->routeIs('admin.booking-ruang.*')
                                    ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                                <i class="bi bi-building"></i>

                                <span>Permintaan Booking Ruang Rapat</span>

                            </a>
                        @endif

                    </div>

                </div>
            @endif

            {{-- Data Laporan --}}
            @if ($canDataLaporan)
                <div x-data="{ open: false }">

                    <button @click="open=!open"
                        class="w-full flex items-center justify-between rounded-xl px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                        <div class="flex items-center gap-3">

                            <i class="bi bi-clipboard-data"></i>

                            <span>

                                Data Laporan

                            </span>

                        </div>

                        <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'">

                        </i>

                    </button>

                    <div x-show="open" x-collapse class="mt-2 space-y-1">
                        <a href="{{ route('admin.program-prioritas.index') }}"
                            class="{{ request()->routeIs('admin.program-prioritas.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-kanban"></i>

                            <span>

                                Laporan Prioritas

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan-aktivitas.index') }}"
                            class="{{ request()->routeIs('admin.laporan-aktivitas.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-clipboard-check"></i>

                            <span>

                                Laporan Aktivitas

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan-sub-kegiatan.index') }}"
                            class="{{ request()->routeIs('admin.laporan-sub-kegiatan.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-list-task"></i>

                            <span>

                                Laporan Sub Kegiatan

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan-spj.index') }}"
                            class="{{ request()->routeIs('admin.laporan-spj.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-receipt-cutoff"></i>

                            <span>

                                Laporan SPJ

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan-pad.index') }}"
                            class="{{ request()->routeIs('admin.laporan-pad.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-cash-coin"></i>

                            <span>

                                Laporan PAD

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan-shs.index') }}"
                            class="{{ request()->routeIs('admin.laporan-shs.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-box-seam"></i>

                            <span>

                                Laporan SHS

                            </span>

                        </a>
                        <a href="{{ route('admin.laporan.kak') }}"
                            class="{{ request()->routeIs('admin.laporan.kak.*')
                                ? 'bg-blue-50 dark:bg-slate-800 text-blue-600'
                                : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}
                        flex items-center gap-3 rounded-lg px-5 py-2.5 transition">

                            <i class="bi bi-file-earmark-richtext"></i>

                            <span>

                                Laporan KAK

                            </span>

                        </a>
                    </div>

                </div>
            @endif

        </nav>

    </div>

    {{-- Footer --}}
    <div class="border-t border-slate-200 dark:border-slate-800 p-5">

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                class="w-full flex items-center gap-3 rounded-xl px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">

                <i class="bi bi-box-arrow-right"></i>

                <span>

                    Logout

                </span>

            </button>

        </form>

    </div>

</aside>
