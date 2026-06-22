@extends('user.layouts.app')

@section('title', 'Pengajuan BBM')
@section('page_title', 'Pengajuan BBM')
@section('breadcrumb', 'Pengajuan BBM')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Daftar Pengajuan BBM
                    </h2>
                    <p class="text-sm text-slate-500">
                        Data pengajuan BBM Anda.
                    </p>
                </div>

                <a href="{{ route('user.bbm.create') }}"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                    + Pengajuan BBM
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">No Plat</th>
                            <th class="py-3 px-3">Foto Mobil</th>
                            <th class="py-3 px-3">Liter</th>
                            <th class="py-3 px-3">Status Pengajuan</th>
                            <th class="py-3 px-3">Status Nota</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bbms as $item)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-4 px-3">{{ $loop->iteration }}</td>

                                <td class="py-4 px-3 font-semibold text-slate-800">
                                    {{ $item->bbm_no_plat }}
                                </td>

                                <td class="py-4 px-3">
                                    @if ($item->bbm_foto_mobil_file)
                                        <a href="{{ $item->bbm_foto_mobil_file }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="py-4 px-3">
                                    {{ number_format($item->bbm_liter, 2, ',', '.') }} L
                                </td>

                                <td class="py-4 px-3">
                                    {{ $item->bbm_status_pengajuan }}
                                </td>

                                <td class="py-4 px-3">
                                    {{ $item->bbm_status_laporan }}
                                </td>

                                <td class="py-4 px-3 text-right">
                                    @if ($item->bbm_status_pengajuan === 'Pengajuan Diterima' && $item->bbm_status_laporan !== 'Laporan Nota Diterima')
                                        <a href="{{ route('user.bbm.show', $item->bbm_uid) }}"
                                            class="inline-block px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                                            Upload Data
                                        </a>
                                    @elseif ($item->bbm_status_laporan === 'Laporan Nota Diterima')
                                        <a href="{{ route('user.bbm.show', $item->bbm_uid) }}"
                                            class="inline-block px-4 py-2 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700">
                                            Lihat Nota
                                        </a>
                                    @else
                                        <a href="{{ route('user.bbm.show', $item->bbm_uid) }}"
                                            class="inline-block px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">
                                    Belum ada pengajuan BBM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection
