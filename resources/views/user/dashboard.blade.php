@extends('user.layouts.app')

@section('title', 'Dashboard User - SAPLARIN')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard User')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-200">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <p class="text-sm text-slate-500">
                    Selamat datang kembali,
                </p>

                <h2 class="text-2xl font-extrabold text-slate-900 mt-1">
                    {{ session('pegawai_nama') ?? 'User' }}
                </h2>

                <p class="text-slate-500 mt-2">
                    Silakan lanjutkan pengelolaan dokumen sesuai role aktif Anda.
                </p>
            </div>

            <div class="rounded-2xl bg-blue-50 px-5 py-4">
                <p class="text-xs text-blue-500 font-semibold uppercase">
                    Role Aktif
                </p>
                <p class="text-blue-800 font-bold mt-1">
                    {{ session('active_role') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-3xl p-6 text-white shadow-lg shadow-blue-100">

        <p class="text-sm opacity-80">
            Status Hari Ini
        </p>

        <h3 class="text-3xl font-extrabold mt-3">
            Siap Bekerja
        </h3>

        <p class="text-sm opacity-80 mt-2">
            Pastikan dokumen yang diinput sudah sesuai sebelum dikirim.
        </p>

    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mt-6">

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500">Total Dokumen</p>
        <h3 class="text-3xl font-extrabold text-slate-900 mt-2">0</h3>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500">Draft</p>
        <h3 class="text-3xl font-extrabold text-slate-900 mt-2">0</h3>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500">Menunggu</p>
        <h3 class="text-3xl font-extrabold text-yellow-500 mt-2">0</h3>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500">Selesai</p>
        <h3 class="text-3xl font-extrabold text-green-600 mt-2">0</h3>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-slate-900">
                Menu Cepat
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @if (session('active_role') == 'Penginput SPJ' || session('active_role') == 'Pegawai')
                <a href="{{ route('user.spj.index') }}"
                    class="rounded-3xl border border-slate-200 p-5 hover:border-blue-400 hover:bg-blue-50 transition">
                    <div class="text-3xl mb-3">📄</div>
                    <h4 class="font-bold text-slate-900">Input SPJ</h4>
                    <p class="text-sm text-slate-500 mt-1">
                        Buat dan kelola dokumen SPJ.
                    </p>
                </a>
            @endif

            @if (session('active_role') == 'Penginput KAK')
                <a href="{{ route('user.kak.index') }}"
                    class="rounded-3xl border border-slate-200 p-5 hover:border-blue-400 hover:bg-blue-50 transition">
                    <div class="text-3xl mb-3">📝</div>
                    <h4 class="font-bold text-slate-900">Input KAK</h4>
                    <p class="text-sm text-slate-500 mt-1">
                        Buat dan kelola dokumen KAK.
                    </p>
                </a>
            @endif

            @if (session('active_role') == 'Penginput PWA')
                <a href="{{ route('user.pwa.index') }}"
                    class="rounded-3xl border border-slate-200 p-5 hover:border-blue-400 hover:bg-blue-50 transition">
                    <div class="text-3xl mb-3">📊</div>
                    <h4 class="font-bold text-slate-900">Input PWA</h4>
                    <p class="text-sm text-slate-500 mt-1">
                        Kelola laporan PWA.
                    </p>
                </a>
            @endif

            <a href="{{ route('user.riwayat') }}"
                class="rounded-3xl border border-slate-200 p-5 hover:border-slate-400 hover:bg-slate-50 transition">
                <div class="text-3xl mb-3">🕘</div>
                <h4 class="font-bold text-slate-900">Riwayat</h4>
                <p class="text-sm text-slate-500 mt-1">
                    Lihat riwayat dokumen Anda.
                </p>
            </a>

        </div>

    </div>

    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <h3 class="text-lg font-bold text-slate-900 mb-4">
            Informasi User
        </h3>

        <div class="space-y-4 text-sm">

            <div>
                <p class="text-slate-500">Nama</p>
                <p class="font-semibold text-slate-800">
                    {{ session('pegawai_nama') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-slate-500">NIP</p>
                <p class="font-semibold text-slate-800">
                    {{ session('pegawai_nip') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-slate-500">Role</p>
                <p class="font-semibold text-blue-700">
                    {{ session('active_role') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection