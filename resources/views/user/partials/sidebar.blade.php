@php
    $activeRole = session('active_role', 'Pegawai');

    $isOperator = $activeRole === 'Operator';

    $canBBMKegiatan = in_array($activeRole, ['Pegawai', 'Operator']);
    $canKinerjaPrioritas = $isOperator;
    $canLaporanAktivitas = $isOperator;
    $canLaporanSubKegiatan = $isOperator;
    $canInputSPJ = $isOperator;
    $canSHS = $isOperator;
@endphp

<aside id="sidebar"
    class="fixed left-0 top-0 bottom-0 z-50 w-72 flex flex-col bg-white border-r border-slate-200
    transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

    <div class="p-6">

        <div class="flex items-center gap-3">

            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                <img src="{{ asset('image/pemprov.png') }}" class="w-9 h-9 object-contain">
            </div>

            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-slate-900">
                    SAPLARIN
                </h1>

                <p class="text-xs text-slate-500">
                    Pelaporan Internal
                </p>
            </div>
            <div class="flex justify-end p-4 lg:hidden">

                <button id="btnCloseSidebar" class="w-10 h-10 rounded-xl hover:bg-slate-100 text-slate-700">

                    ✕

                </button>

            </div>
        </div>

        <div
            class="mt-6 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 p-4 text-white shadow-lg shadow-blue-100">
            <p class="text-xs opacity-80">Login sebagai</p>

            <button onclick="openRoleModal()" class="mt-1 text-left w-full">
                <div class="flex items-center justify-between">
                    <span class="font-semibold">
                        {{ $activeRole }}
                    </span>

                    <span class="text-xs bg-white/20 px-2 py-1 rounded-lg">
                        Ganti
                    </span>
                </div>
            </button>
        </div>

    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-4">

        <a href="{{ route('user.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
            {{ request()->routeIs('user.dashboard')
                ? 'bg-blue-50 text-blue-700'
                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            <span>🏠</span>
            <span>Dashboard</span>
        </a>

        @if ($canBBMKegiatan)
            <a href="{{ route('user.bbm.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.bbm*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>⛽</span>
                <span>BBM Kegiatan</span>
            </a>
        @endif

        @if ($canKinerjaPrioritas)
            <a href="{{ route('user.program-prioritas.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.program-prioritas*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>🎯</span>
                <span>Kinerja Prioritas</span>
            </a>
        @endif

        @if ($canLaporanAktivitas)
            <a href="{{ route('user.laporan-aktivitas.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.laporan-aktivitas*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📋</span>
                <span>Laporan Aktivitas</span>
            </a>
        @endif

        @if ($canLaporanSubKegiatan)
            <a href="{{ route('user.laporan-sub-kegiatan.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.laporan-sub-kegiatan*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📈</span>
                <span>Laporan Sub Kegiatan</span>
            </a>
        @endif

        @if ($canInputSPJ)
            <a href="{{ route('user.spj.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.spj*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>💰</span>
                <span>Input SPJ</span>
            </a>
        @endif

        @if ($canSHS)
            <a href="{{ route('user.shs.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
        {{ request()->routeIs('user.shs*')
            ? 'bg-blue-50 text-blue-700'
            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📦</span>
                <span>Usulan SHS</span>
            </a>
        @endif

        <a href="javascript:void(0)"
            class="flex items-center justify-between px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed">
            <div class="flex items-center gap-3">
                <span>📝</span>
                <span>Permintaan KAK</span>
            </div>

            <span class="text-[10px] bg-slate-200 px-2 py-1 rounded-full">
                Soon
            </span>
        </a>

        <a href="javascript:void(0)"
            class="flex items-center justify-between px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed">
            <div class="flex items-center gap-3">
                <span>🛢️</span>
                <span>Permintaan BBM Rutin</span>
            </div>

            <span class="text-[10px] bg-slate-200 px-2 py-1 rounded-full">
                Soon
            </span>
        </a>

        <a href="javascript:void(0)"
            class="flex items-center justify-between px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed">
            <div class="flex items-center gap-3">
                <span>📊</span>
                <span>PWA</span>
            </div>

            <span class="text-[10px] bg-slate-200 px-2 py-1 rounded-full">
                Soon
            </span>
        </a>

        <a href="javascript:void(0)"
            class="flex items-center justify-between px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed">
            <div class="flex items-center gap-3">
                <span>🕘</span>
                <span>Riwayat</span>
            </div>

            <span class="text-[10px] bg-slate-200 px-2 py-1 rounded-full">
                Soon
            </span>
        </a>

    </nav>

    <div class="p-4 border-t border-slate-200">
        <a href="{{ route('logout') }}"
            class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-red-50 text-red-600 font-semibold hover:bg-red-100 transition">
            Logout
        </a>
    </div>

    @include('user.partials.role-modal')

</aside>
