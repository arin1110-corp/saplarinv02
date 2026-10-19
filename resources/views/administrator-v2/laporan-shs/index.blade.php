@extends('administrator-v2.layouts.app')

@section('title', 'Laporan SHS')
@section('page-title', 'Laporan SHS')
@section('page-description', 'Verifikasi usulan Standar Harga Satuan')

@section('content')
    {{-- ALERT --}}
    @if (session('success'))
        <div
            class="mb-6 rounded-2xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-5 py-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                <span class="text-green-700 dark:text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-x-circle-fill text-red-600 text-xl"></i>
                <span class="text-red-700 dark:text-red-300">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-5 py-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold text-red-700 dark:text-red-300 mb-2">Terjadi Kesalahan</h4>
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Laporan SHS</h2>
            <p class="text-slate-500 dark:text-slate-400">
                Verifikasi usulan Standar Harga Satuan dari seluruh operator.
            </p>
        </div>

        <button type="button" onclick="openExportModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-white font-semibold transition">
            <i class="bi bi-file-earmark-excel"></i>
            Export Excel
        </button>
    </div>

    {{-- FILTER --}}
    <div class="mb-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
        <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">

            <div class="relative xl:col-span-2">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari barang, unit, kelompok, operator, NIP..."
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white pl-11 pr-4 py-3">
            </div>

            <select name="tahun"
                class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-4 py-3">
                <option value="">Semua Tahun</option>
                @foreach ($tahunOptions ?? collect() as $tahun)
                    <option value="{{ $tahun }}" @selected((string) request('tahun') === (string) $tahun)>
                        {{ $tahun }}
                    </option>
                @endforeach
            </select>

            <select name="unit"
                class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-4 py-3">
                <option value="">Semua Unit</option>
                @foreach ($unitOptions ?? collect() as $unit)
                    <option value="{{ $unit }}" @selected(request('unit') === $unit)>
                        {{ $unit }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-4 py-3">
                <option value="">Semua Status</option>
                <option value="Diajukan" @selected(request('status') === 'Diajukan')>Diajukan</option>
                <option value="Diverifikasi" @selected(request('status') === 'Diverifikasi')>Diverifikasi</option>
                <option value="Diajukan ke BPKAD" @selected(request('status') === 'Diajukan ke BPKAD')>Diajukan ke BPKAD</option>
                <option value="Tidak Diajukan" @selected(request('status') === 'Tidak Diajukan')>Tidak Diajukan</option>
            </select>

            <div class="flex flex-wrap gap-2 sm:col-span-2 xl:col-span-5">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-3 text-white font-semibold">
                    <i class="bi bi-funnel"></i>
                    Terapkan Filter
                </button>

                <a href="{{ url()->current() }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-3 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 dark:bg-slate-800">
                    <tr>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Aksi</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">No</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Tahun</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Unit</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Barang</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Kode Kelompok</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Kelompok</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Harga Usulan</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Survey</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Operator</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">Status SHS</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($shs as $item)
                        @php
                            $referensiCount = $item->referensiHarga ? $item->referensiHarga->count() : 0;

                            $status = $item->shs_status ?? '';
                        @endphp

                        <tr
                            class="border-t border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            {{-- AKSI --}}
                            <td class="px-4 py-4 min-w-[190px]">
                                <div class="flex flex-col items-start gap-2">
                                    <button type="button" onclick='detailSHS(@json($item->loadMissing('referensiHarga')))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-800 px-3 py-2 text-xs font-medium text-white">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </button>

                                    @if ($status === 'Diajukan')
                                        <button type="button" onclick='verifikasiSHS(@json($item))'
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-medium text-white">
                                            <i class="bi bi-check-circle"></i>
                                            Verifikasi
                                        </button>

                                        <form method="POST" action="{{ route('admin.shs.nonaktif', $item->shs_uid) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-3 py-2 text-xs text-white"
                                                onclick="return confirm('Ubah usulan menjadi Tidak Diajukan?')">
                                                <i class="bi bi-x-circle"></i>
                                                Tidak Diajukan
                                            </button>
                                        </form>
                                    @elseif ($status === 'Tidak Diajukan')
                                        <form method="POST"
                                            action="{{ route('admin.laporan-shs.aktif', $item->shs_uid) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-3 py-2 text-xs text-white"
                                                onclick="return confirm('Ajukan kembali usulan ini?')">
                                                <i class="bi bi-send"></i>
                                                Diajukan Kembali
                                            </button>
                                        </form>
                                    @endif

                                    @if ($status === 'Diverifikasi')
                                        <button type="button"
                                            onclick='konfirmasiBPKAD(@json($item->shs_uid), @json($item->shs_barang))'
                                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 px-3 py-2 text-xs font-semibold text-white">
                                            <i class="bi bi-send-check"></i>
                                            Ajukan ke BPKAD
                                        </button>
                                    @endif

                                    @if ($status === 'Diajukan ke BPKAD')
                                        <button type="button"
                                            onclick='mintaPerbaikanSHS(@json($item->shs_uid), @json($item->shs_barang), @json($item->shs_catatan_admin))'
                                            class="inline-flex items-center gap-2 rounded-lg bg-orange-600 hover:bg-orange-700 px-3 py-2 text-xs font-semibold text-white">
                                            <i class="bi bi-arrow-return-left"></i>
                                            Minta Perbaikan
                                        </button>
                                    @endif
                                </div>
                            </td>

                            {{-- NOMOR --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ method_exists($shs, 'firstItem') && $shs->firstItem() ? $shs->firstItem() + $loop->index : $loop->iteration }}
                            </td>

                            {{-- TAHUN --}}
                            <td class="px-4 py-4 whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                {{ $item->shs_tahun }}
                            </td>

                            {{-- UNIT --}}
                            <td class="px-4 py-4 min-w-[170px]">
                                <div class="font-medium text-slate-800 dark:text-white">
                                    {{ $item->shs_unit_nama }}
                                </div>
                                @if ($item->shs_unit_kode)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_unit_kode }}
                                    </div>
                                @endif
                            </td>

                            {{-- BARANG --}}
                            <td class="px-4 py-4 min-w-[220px]">
                                <div class="font-semibold text-slate-800 dark:text-white">
                                    {{ $item->shs_barang }}
                                </div>

                                @if ($item->shs_merek || $item->shs_tipe)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_merek }}
                                        @if ($item->shs_merek && $item->shs_tipe)
                                            ·
                                        @endif
                                        {{ $item->shs_tipe }}
                                    </div>
                                @endif
                            </td>

                            {{-- KODE KELOMPOK --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $item->shs_kode_kelompok }}
                            </td>

                            {{-- KELOMPOK --}}
                            <td class="px-4 py-4 min-w-[180px]">
                                {{ $item->shs_kelompok_barang }}
                            </td>

                            {{-- HARGA --}}
                            <td class="px-4 py-4 font-semibold whitespace-nowrap">
                                Rp {{ number_format((float) $item->shs_harga, 0, ',', '.') }}
                            </td>

                            {{-- SURVEY --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if ($referensiCount > 0)
                                    <button type="button" onclick='detailSHS(@json($item->loadMissing('referensiHarga')))'
                                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 dark:bg-emerald-900/20 px-3 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                        <i class="bi bi-bar-chart"></i>
                                        {{ min($referensiCount, 3) }} Survey
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400">Belum ada survey</span>
                                @endif
                            </td>

                            {{-- OPERATOR --}}
                            <td class="px-4 py-4 min-w-[170px]">
                                <div class="font-semibold text-slate-800 dark:text-white">
                                    {{ $item->shs_operator_nama }}
                                </div>
                                @if ($item->shs_operator_nip)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $item->shs_operator_nip }}
                                    </div>
                                @endif
                            </td>

                            {{-- STATUS SHS --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if ($status === 'Diajukan')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 px-3 py-1 text-xs font-semibold text-green-700 dark:text-green-300">
                                        Diajukan
                                    </span>
                                @elseif ($status === 'Diverifikasi')
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 dark:bg-blue-900/20 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        Diverifikasi
                                    </span>
                                @elseif ($status === 'Diajukan ke BPKAD')
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-900/20 px-3 py-1 text-xs font-semibold text-amber-700 dark:text-amber-300">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Diajukan ke BPKAD
                                    </span>
                                @elseif ($status === 'Tidak Diajukan')
                                    <span
                                        class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">
                                        Tidak Diajukan
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $status ?: 'Belum ada status' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-16 text-center">
                                <i class="bi bi-inbox text-5xl text-slate-300 dark:text-slate-700 mb-4 block"></i>
                                <div class="font-semibold text-slate-700 dark:text-slate-300">
                                    Data SHS tidak ditemukan
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    Coba ubah filter pencarian.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if (method_exists($shs, 'hasPages') && $shs->hasPages())
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-t border-slate-200 dark:border-slate-800 px-5 py-4">
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $shs->firstItem() }}</span>
                    -
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $shs->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $shs->total() }}</span>
                    data
                </div>
                {{ $shs->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL DETAIL --}}
    <div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div
            class="w-full max-w-7xl max-h-[94vh] overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl flex flex-col">
            <div
                class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5 shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Detail Usulan SHS</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Detail barang dan referensi survey harga.</p>
                </div>
                <button type="button" onclick="closeDetail()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="mb-8">
                    <h4 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Data Barang / Jasa</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @foreach ([
            'd_tahun' => 'Tahun',
            'd_unit' => 'Unit',
            'd_kode' => 'Kode Kelompok',
            'd_kelompok' => 'Kelompok',
            'd_barang' => 'Uraian / Nama Barang',
            'd_merek' => 'Merk',
            'd_tipe' => 'Tipe',
            'd_satuan' => 'Satuan',
            'd_tkdn' => 'TKDN',
            'd_harga' => 'Harga Rekomendasi / Usulan',
            'd_dasar_usulan' => 'Dasar Usulan',
            'd_spesifikasi' => 'Spesifikasi',
            'd_keterangan' => 'Keterangan',
        ] as $id => $label)
                            <div class="detail-box">
                                <span>{{ $label }}</span>
                                <strong id="{{ $id }}">-</strong>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-slate-700 pt-7">
                    <h4 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Referensi Survey Harga</h4>
                    <div id="d_survey_count" class="mb-4 text-sm text-slate-500">0 Survey</div>
                    <div id="d_referensi" class="grid grid-cols-1 lg:grid-cols-3 gap-5"></div>
                </div>

                <div class="border-t border-slate-200 dark:border-slate-700 pt-7 mt-7">
                    <h4 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Data Operator</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="detail-box"><span>Nama Operator</span><strong id="d_operator">-</strong></div>
                        <div class="detail-box"><span>NIP Operator</span><strong id="d_nip">-</strong></div>
                        <div class="detail-box"><span>Status</span><strong id="d_status">-</strong></div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex justify-end">
                <button type="button" onclick="closeDetail()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL VERIFIKASI --}}
    <div id="modalVerifikasi"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div
            class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Verifikasi SHS</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Berikan keputusan terhadap usulan SHS.</p>
                </div>
                <button type="button" onclick="closeVerifikasi()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="formVerifikasi" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Barang</label>
                        <input id="v_barang" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Harga Rekomendasi / Usulan</label>
                        <input id="v_harga" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3 font-semibold">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Catatan Admin</label>
                        <textarea name="shs_catatan_admin" rows="5"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Masukkan catatan verifikasi jika diperlukan..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">
                    <button type="button" onclick="closeVerifikasi()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-semibold text-white">
                        <i class="bi bi-check-circle me-2"></i>Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI BPKAD --}}
    <div id="modalBPKAD" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div
            class="w-full max-w-lg rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
                    <i class="bi bi-send-check text-amber-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Ajukan ke BPKAD</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Konfirmasi pengajuan SHS.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
                Status usulan berikut akan diubah menjadi <strong>Diajukan ke BPKAD</strong>:
            </p>

            <div id="bpkadBarang" class="rounded-xl bg-slate-100 dark:bg-slate-800 p-4 mb-6 font-semibold"></div>

            <form id="formBPKAD" method="POST">
                @csrf
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeBPKAD()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-amber-500 hover:bg-amber-600 px-5 py-2.5 text-white font-semibold">
                        <i class="bi bi-send-check me-2"></i>Ya, Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EXPORT --}}
    <div id="modalExport" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div
            class="w-full max-w-3xl max-h-[92vh] overflow-y-auto rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Export Laporan SHS</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Pilih filter untuk export.</p>
                </div>
                <button type="button" onclick="closeExportModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.laporan-shs.export') }}" method="GET">
                @foreach (['shs_tahun', 'shs_unit_nama', 'shs_kode_kelompok', 'shs_kelompok_barang', 'shs_barang', 'shs_merek', 'shs_tipe', 'shs_spesifikasi', 'shs_satuan', 'shs_harga', 'shs_tkdn', 'shs_kelompok', 'shs_dasar_usulan', 'shs_keterangan', 'shs_status', 'shs_operator_nama', 'created_at'] as $field)
                    <input type="hidden" name="field[]" value="{{ $field }}">
                @endforeach

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 text-sm font-semibold">Tahun</label>
                        <select name="tahun"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunOptions ?? collect() as $tahun)
                                <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold">Status SHS</label>
                        <select name="status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">
                            <option value="">Semua Status</option>
                            <option value="Diajukan" @selected(request('status') === 'Diajukan')>Diajukan</option>
                            <option value="Diverifikasi" @selected(request('status') === 'Diverifikasi')>Diverifikasi</option>
                            <option value="Diajukan ke BPKAD" @selected(request('status') === 'Diajukan ke BPKAD')>Diajukan ke BPKAD</option>
                            <option value="Tidak Diajukan" @selected(request('status') === 'Tidak Diajukan')>Tidak Diajukan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">
                    <button type="button" onclick="closeExportModal()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 text-white font-semibold">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL MINTA PERBAIKAN --}}
    <div id="modalPerbaikan"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="w-full max-w-2xl rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 px-6 py-5">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">
                        Minta Perbaikan SHS
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Catatan ini akan disampaikan kepada operator pengusul.
                    </p>
                </div>

                <button type="button" onclick="closePerbaikanSHS()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="formPerbaikanSHS" method="POST">
                @csrf

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            Barang / Jasa
                        </label>
                        <input id="p_barang" type="text" readonly
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 px-4 py-3 text-slate-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            Catatan Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="p_catatan" name="shs_catatan_admin" rows="5" required maxlength="5000"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-800 dark:text-white"
                            placeholder="Jelaskan bagian yang perlu diperbaiki oleh operator..."></textarea>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            Status usulan akan dikembalikan menjadi Draft.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 px-6 py-5">
                    <button type="button" onclick="closePerbaikanSHS()"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 text-slate-700 dark:text-slate-200">
                        Batal
                    </button>

                    <button type="submit"
                        onclick="return confirm('Kirim catatan perbaikan dan kembalikan status usulan menjadi Draft?')"
                        class="rounded-xl bg-orange-600 hover:bg-orange-700 px-5 py-2.5 font-semibold text-white">
                        <i class="bi bi-send me-2"></i>
                        Kirim Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            if (!document.querySelector('.fixed.inset-0.z-50.flex')) {
                document.body.classList.remove('overflow-hidden');
            }
        }

        function formatRupiah(value) {
            if (value === null || value === undefined || value === '') {
                return 'Rp 0';
            }

            let number;

            if (typeof value === 'number') {
                number = value;
            } else {
                let text = String(value).replace(/Rp/gi, '').trim();

                if (/^\d{1,3}(\.\d{3})+(,\d+)?$/.test(text)) {
                    text = text.replace(/\./g, '').replace(',', '.');
                } else {
                    text = text.replace(/,/g, '');
                }

                number = Number(text);
            }

            if (Number.isNaN(number)) return 'Rp 0';

            return 'Rp ' + Math.round(number).toLocaleString('id-ID');
        }

        function escapeHtml(value) {
            if (value === null || value === undefined) return '';

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function normalizeLink(value) {
            if (!value) return '';

            const link = String(value).trim();

            if (!/^https?:\/\//i.test(link)) return '';

            return link;
        }

        function detailSHS(item) {
            if (!item) return;

            const values = {
                d_tahun: item.shs_tahun ?? '-',
                d_unit: item.shs_unit_nama ?? '-',
                d_kode: item.shs_kode_kelompok ?? '-',
                d_kelompok: item.shs_kelompok_barang ?? '-',
                d_barang: item.shs_barang ?? '-',
                d_merek: item.shs_merek ?? '-',
                d_tipe: item.shs_tipe ?? '-',
                d_satuan: item.shs_satuan ?? '-',
                d_tkdn: item.shs_tkdn !== null && item.shs_tkdn !== undefined && item.shs_tkdn !== '' ?
                    item.shs_tkdn + ' %' : '-',
                d_harga: formatRupiah(item.shs_harga),
                d_dasar_usulan: item.shs_dasar_usulan ?? '-',
                d_spesifikasi: item.shs_spesifikasi ?? '-',
                d_keterangan: item.shs_keterangan ?? '-',
                d_operator: item.shs_operator_nama ?? '-',
                d_nip: item.shs_operator_nip ?? '-',
                d_status: item.shs_status ?? '-'
            };

            Object.keys(values).forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = values[id];
            });

            const container = document.getElementById('d_referensi');
            const surveyCount = document.getElementById('d_survey_count');

            if (!container) return;

            const referensi = Array.isArray(item.referensi_harga) ?
                item.referensi_harga.slice(0, 3) :
                (Array.isArray(item.referensiHarga) ? item.referensiHarga.slice(0, 3) : []);

            if (surveyCount) {
                surveyCount.textContent = referensi.length + ' Survey';
            }

            let html = '';

            for (let index = 0; index < 3; index++) {
                const ref = referensi[index] ?? null;
                const nomor = index + 1;

                if (!ref) {
                    html += `
                        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-6 text-center">
                            <div class="font-bold text-slate-500">Survey ${nomor}</div>
                            <div class="text-sm text-slate-400 mt-2">Belum ada referensi harga.</div>
                        </div>`;
                    continue;
                }

                const harga = ref.shs_referensi_harga ?? ref.harga ?? '';
                const link = normalizeLink(ref.shs_referensi_link ?? ref.link ?? '');
                const safeLink = escapeHtml(link);

                html += `
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-5 py-4 bg-slate-100 dark:bg-slate-800 font-bold">Survey ${nomor}</div>
                        <div class="p-5 space-y-3">
                            <div class="text-xs uppercase text-slate-500">Harga</div>
                            <div class="text-xl font-bold text-slate-800 dark:text-white">${formatRupiah(harga)}</div>
                            <div class="text-xs uppercase text-slate-500">Link Survey</div>
                            ${safeLink
                                ? `<a href="${safeLink}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">Buka Link Survey</a>`
                                : `<div class="text-sm text-slate-400">Tidak ada link survey</div>`
                            }
                        </div>
                    </div>`;
            }

            container.innerHTML = html;
            showModal('modalDetail');
        }

        function closeDetail() {
            hideModal('modalDetail');
        }

        function verifikasiSHS(item) {
            if (!item) return;

            const barang = document.getElementById('v_barang');
            const harga = document.getElementById('v_harga');
            const form = document.getElementById('formVerifikasi');

            if (barang) barang.value = item.shs_barang ?? '';
            if (harga) harga.value = formatRupiah(item.shs_harga);

            if (form) {
                form.action = @json(url('/admin/laporan-shs')) +
                    '/' + encodeURIComponent(item.shs_uid) +
                    '/verifikasi';
            }

            showModal('modalVerifikasi');
        }

        function closeVerifikasi() {
            const form = document.getElementById('formVerifikasi');
            if (form) form.reset();

            hideModal('modalVerifikasi');
        }

        function konfirmasiBPKAD(uid, barang) {
            const form = document.getElementById('formBPKAD');
            const label = document.getElementById('bpkadBarang');

            if (form) {
                form.action = @json(url('/admin/laporan-shs')) +
                    '/' + encodeURIComponent(uid) +
                    '/bpkad';
            }

            if (label) label.textContent = barang || '-';

            showModal('modalBPKAD');
        }

        function closeBPKAD() {
            hideModal('modalBPKAD');
        }

        function openExportModal() {
            showModal('modalExport');
        }

        function closeExportModal() {
            hideModal('modalExport');
        }

        document.addEventListener('DOMContentLoaded', function() {
            [
                'modalDetail',
                'modalVerifikasi',
                'modalBPKAD',
                'modalExport'
            ].forEach(id => {
                const modal = document.getElementById(id);
                if (!modal) return;

                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        hideModal(id);
                    }
                });
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') return;

            [
                'modalDetail',
                'modalVerifikasi',
                'modalBPKAD',
                'modalExport'
            ].forEach(id => {
                const modal = document.getElementById(id);

                if (modal && !modal.classList.contains('hidden')) {
                    hideModal(id);
                }
            });
        });

        function mintaPerbaikanSHS(uid, barang, catatan) {
            const form = document.getElementById('formPerbaikanSHS');
            const inputBarang = document.getElementById('p_barang');
            const inputCatatan = document.getElementById('p_catatan');

            if (form) {
                form.action = @json(url('/admin/laporan-shs')) +
                    '/' + encodeURIComponent(uid) +
                    '/minta-perbaikan';
            }

            if (inputBarang) {
                inputBarang.value = barang || '';
            }

            if (inputCatatan) {
                inputCatatan.value = catatan || '';
            }

            showModal('modalPerbaikan');
        }

        function closePerbaikanSHS() {
            hideModal('modalPerbaikan');
        }
    </script>
@endpush

@push('styles')
    <style>
        input,
        textarea,
        select {
            transition: .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgb(37 99 235 / .15);
        }

        textarea {
            resize: vertical;
        }

        .detail-box {
            min-width: 0;
            padding: 16px;
            border: 1px solid rgb(226 232 240);
            border-radius: 16px;
            background: rgb(248 250 252);
        }

        .dark .detail-box {
            border-color: rgb(51 65 85);
            background: rgb(15 23 42);
        }

        .detail-box>span {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            line-height: 1.3;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: rgb(100 116 139);
        }

        .dark .detail-box>span {
            color: rgb(148 163 184);
        }

        .detail-box>strong {
            display: block;
            color: rgb(30 41 59);
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .dark .detail-box>strong {
            color: rgb(226 232 240);
        }

        nav[role="navigation"] {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            min-width: 38px;
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
    </style>
@endpush
