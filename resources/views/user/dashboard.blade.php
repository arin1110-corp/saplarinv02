@extends('user.layouts.app')

@section('title', 'Dashboard User - SAPLARIN')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard User')

@section('content')

<div class="space-y-6">

    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <p class="text-blue-100 text-sm">
                    Selamat datang,
                </p>

                <h2 class="text-2xl md:text-3xl font-extrabold mt-1">
                    {{ session('pegawai_nama') ?? 'User' }}
                </h2>

                <p class="text-blue-100 text-sm mt-2">
                    Sistem Administrasi Pelaporan Internal Dinas Kebudayaan Provinsi Bali
                </p>
            </div>

            <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4">
                <p class="text-xs text-blue-100">
                    Role Aktif
                </p>

                <p class="font-bold text-lg">
                    {{ session('active_role') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

        <h3 class="text-lg font-bold text-slate-900 mb-6">
            Identitas Pegawai
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    Nama
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_nama') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    NIP
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_nip') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    Jabatan
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_jabatan') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    Bidang
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_bidang') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    Email
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_email') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                <p class="text-slate-500 mb-1">
                    No. HP
                </p>

                <p class="font-semibold text-slate-900">
                    {{ session('pegawai_hp') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection