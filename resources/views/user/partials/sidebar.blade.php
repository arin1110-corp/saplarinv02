@php
    $activeRole = session('active_role', 'Pegawai');

    $isOperator = $activeRole === 'Operator';

    $canBBMKegiatan = in_array($activeRole, ['Pegawai', 'Operator']);
    $canKAK = $isOperator;
    $canSPJ = $isOperator;
    $canBBMRutin = $isOperator;
    $canLaporanAktivitas = $isOperator;
    $canPWA = $isOperator;
@endphp

<aside class="fixed left-0 top-0 bottom-0 z-40 hidden lg:flex w-72 flex-col bg-white border-r border-slate-200">

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

        @if ($canKAK)
            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.kak*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📝</span>
                <span>Permintaan KAK</span>
            </a>
        @endif

        @if ($canSPJ)
            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.spj*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📄</span>
                <span>Permintaan SPJ</span>
            </a>
        @endif

        @if ($canBBMRutin)
            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
                {{ request()->routeIs('user.bbm-rutin*')
                    ? 'bg-blue-50 text-blue-700'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>🛢️</span>
                <span>Permintaan BBM Rutin</span>
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

        @if ($canPWA)
            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
        {{ request()->routeIs('user.pwa*')
            ? 'bg-blue-50 text-blue-700'
            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>📊</span>
                <span>PWA</span>
            </a>
        @endif

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl font-medium transition
            {{ request()->routeIs('user.riwayat')
                ? 'bg-blue-50 text-blue-700'
                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            <span>🕘</span>
            <span>Riwayat</span>
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
