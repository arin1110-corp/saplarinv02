@extends('user.layouts.app')

@section('title', 'Input SPJ')
@section('page_title', 'Input SPJ')
@section('breadcrumb', 'Input SPJ')

@php
    $tahunList = $pagus->pluck('spj_pagu_tahun')->unique()->sortDesc()->values();
@endphp

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

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-6 text-white">
            <h2 class="text-2xl font-bold">Input SPJ</h2>
            <p class="text-blue-100 text-sm mt-2">
                Operator menginput uraian SPJ, nominal, tanggal SPJ, dan file bukti SPJ berdasarkan unit pengampu pagu.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Tahun Anggaran
                    </label>

                    <select id="filterTahun"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-slate-800">
                        <option value="">Semua Tahun</option>

                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Filter Unit
                    </label>

                    <select id="filterUnit"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white text-slate-800">
                        <option value="">Semua Unit</option>

                        @foreach ($units as $unit)
                            <option value="{{ $unit->unit_id }}">
                                {{ $unit->unit_kode }} - {{ $unit->unit_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Cari Program / Kegiatan / Sub Kegiatan / Unit
                    </label>

                    <input type="text" id="searchPagu" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        placeholder="Ketik nama unit, program, kegiatan, sub kegiatan, atau kode...">
                </div>

            </div>

            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="text-sm text-slate-500">
                    Menampilkan
                    <span id="showingInfo" class="font-semibold text-slate-800">0</span>
                    data.
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-slate-500">Per halaman</label>

                    <select id="perPage" class="rounded-xl border border-slate-200 px-3 py-2 bg-white text-slate-800">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="paguWrapper" class="space-y-6">

            @forelse ($pagus as $item)

                @php
                    $totalRealisasi = $item->realisasi->where('spj_status', 'Aktif')->sum('spj_nominal');

                    $sisaPagu = $item->spj_pagu_final - $totalRealisasi;

                    $persenSerapan = $item->spj_pagu_final > 0 ? ($totalRealisasi / $item->spj_pagu_final) * 100 : 0;

                    if ($persenSerapan > 100) {
                        $persenSerapan = 100;
                    }

                    $keywordSearch = strtolower(
                        ($item->unit->unit_kode ?? '') .
                            ' ' .
                            ($item->unit->unit_nama ?? '') .
                            ' ' .
                            ($item->spj_pagu_tahun ?? '') .
                            ' ' .
                            ($item->program->program_kode ?? '') .
                            ' ' .
                            ($item->program->program_nama ?? '') .
                            ' ' .
                            ($item->kegiatan->kegiatan_kode ?? '') .
                            ' ' .
                            ($item->kegiatan->kegiatan_nama ?? '') .
                            ' ' .
                            ($item->subKegiatan->sub_kegiatan_kode ?? '') .
                            ' ' .
                            ($item->subKegiatan->sub_kegiatan_nama ?? ''),
                    );
                @endphp
                @php
                    $tw1 = 0;
                    $tw2 = 0;
                    $tw3 = 0;
                    $tw4 = 0;

                    foreach ($item->realisasi->where('spj_status', 'Aktif') as $spj) {
                        $bulan = $spj->spj_tanggal?->month;

                        if ($bulan >= 1 && $bulan <= 3) {
                            $tw1 += $spj->spj_nominal;
                        } elseif ($bulan >= 4 && $bulan <= 6) {
                            $tw2 += $spj->spj_nominal;
                        } elseif ($bulan >= 7 && $bulan <= 9) {
                            $tw3 += $spj->spj_nominal;
                        } elseif ($bulan >= 10 && $bulan <= 12) {
                            $tw4 += $spj->spj_nominal;
                        }
                    }
                @endphp

                <div class="pagu-card bg-white rounded-3xl border border-slate-200 shadow-sm p-6"
                    data-unit="{{ $item->spj_pagu_unit_id }}" data-tahun="{{ $item->spj_pagu_tahun }}"
                    data-search="{{ $keywordSearch }}">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">

                        <div>
                            <div class="flex flex-wrap gap-2 mb-3">

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Tahun {{ $item->spj_pagu_tahun }}
                                </span>

                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $item->unit->unit_kode ?? '-' }} - {{ $item->unit->unit_nama ?? '-' }}
                                </span>

                            </div>

                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $item->program->program_kode ?? '' }}
                                {{ $item->program->program_nama ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $item->kegiatan->kegiatan_kode ?? '' }}
                                {{ $item->kegiatan->kegiatan_nama ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $item->subKegiatan->sub_kegiatan_kode ?? '' }}
                                {{ $item->subKegiatan->sub_kegiatan_nama ?? '-' }}
                            </p>
                        </div>

                        <button type="button" onclick='openSPJModal(@json($item))'
                            class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            + Input SPJ
                        </button>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <p class="text-xs text-blue-600">Pagu Final</p>
                            <p class="text-xl font-bold text-blue-800">
                                Rp {{ number_format($item->spj_pagu_final, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-green-50 border border-green-100 rounded-2xl p-4">
                            <p class="text-xs text-green-600">Total SPJ</p>
                            <p class="text-xl font-bold text-green-700">
                                Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                            <p class="text-xs text-amber-600">Sisa Pagu</p>
                            <p class="text-xl font-bold text-amber-700">
                                Rp {{ number_format($sisaPagu, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                            <p class="text-xs text-purple-600">Serapan</p>
                            <p class="text-xl font-bold text-purple-700">
                                {{ number_format($persenSerapan, 2, ',', '.') }}%
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">

                        <div class="bg-cyan-50 border border-cyan-100 rounded-2xl p-4">
                            <p class="text-xs text-cyan-600">TW I</p>

                            <p class="text-xl font-bold text-cyan-700">
                                Rp {{ number_format($tw1, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-cyan-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw1 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
                            <p class="text-xs text-indigo-600">TW II</p>

                            <p class="text-xl font-bold text-indigo-700">
                                Rp {{ number_format($tw2, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-indigo-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw2 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                            <p class="text-xs text-purple-600">TW III</p>

                            <p class="text-xl font-bold text-purple-700">
                                Rp {{ number_format($tw3, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-purple-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw3 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div class="bg-pink-50 border border-pink-100 rounded-2xl p-4">
                            <p class="text-xs text-pink-600">TW IV</p>

                            <p class="text-xl font-bold text-pink-700">
                                Rp {{ number_format($tw4, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-pink-500 mt-2">
                                {{ number_format($item->spj_pagu_final > 0 ? ($tw4 / $item->spj_pagu_final) * 100 : 0, 2, ',', '.') }}%
                            </p>
                        </div>

                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-slate-800 mb-3">
                            Riwayat Pagu
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-slate-500">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Jenis</th>
                                        <th class="py-3 px-3">Tanggal</th>
                                        <th class="py-3 px-3 text-right">Nominal</th>
                                        <th class="py-3 px-3">Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($item->detail->sortBy('spj_pagu_detail_urutan') as $detail)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-3 px-3">{{ $loop->iteration }}</td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_jenis }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_tanggal?->format('d/m/Y') ?? '-' }}
                                            </td>

                                            <td class="py-3 px-3 text-right font-semibold">
                                                Rp {{ number_format($detail->spj_pagu_detail_nominal, 0, ',', '.') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $detail->spj_pagu_detail_keterangan ?: '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-slate-500">
                                                Belum ada riwayat pagu.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-800 mb-3">
                            Riwayat SPJ
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="spjRiwayatTable w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left text-slate-500">
                                        <th class="py-3 px-3">No</th>
                                        <th class="py-3 px-3">Tanggal SPJ</th>
                                        <th class="py-3 px-3">Uraian</th>
                                        <th class="py-3 px-3 text-right">Nominal</th>
                                        <th class="py-3 px-3">Operator</th>
                                        <th class="py-3 px-3">File</th>
                                        <th class="py-3 px-3">Tanggal Input</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($item->realisasi->where('spj_status', 'Aktif')->sortByDesc('spj_tanggal') as $spj)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-3 px-3">{{ $loop->iteration }}</td>

                                            <td class="py-3 px-3">
                                                {{ $spj->spj_tanggal?->format('d/m/Y') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $spj->spj_uraian }}
                                            </td>

                                            <td class="py-3 px-3 text-right font-semibold">
                                                Rp {{ number_format($spj->spj_nominal, 0, ',', '.') }}
                                            </td>

                                            <td class="py-3 px-3">
                                                <div class="font-semibold text-slate-800">
                                                    {{ $spj->spj_operator_nama ?? '-' }}
                                                </div>

                                                <div class="text-xs text-slate-500">
                                                    {{ $spj->spj_operator_nip ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="py-3 px-3">
                                                @if ($spj->spj_file)
                                                    <a href="{{ asset($spj->spj_file) }}" target="_blank"
                                                        class="text-blue-600 hover:underline">
                                                        Lihat File
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="py-3 px-3">
                                                {{ $spj->spj_tanggal_input?->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-6 text-center text-slate-500">
                                                Belum ada SPJ.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
                    Belum ada data pagu SPJ aktif.
                </div>
            @endforelse

        </div>

        <div id="emptyFilter"
            class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            Data tidak ditemukan.
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <button type="button" id="prevPage"
                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Sebelumnya
                </button>

                <div id="paginationInfo" class="text-sm text-slate-500 text-center">
                    Halaman 1 dari 1
                </div>

                <button type="button" id="nextPage"
                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Berikutnya
                </button>
            </div>
        </div>

    </div>

    {{-- MODAL INPUT SPJ --}}
    <div id="spjModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Input SPJ
                    </h2>

                    <p id="modal_spj_subkegiatan" class="text-sm text-slate-500"></p>

                    <p id="modal_spj_unit" class="text-sm font-semibold text-blue-600 mt-1"></p>
                </div>

                <button type="button" onclick="closeSPJModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                    ✕
                </button>
            </div>

            <form id="spjForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Uraian SPJ
                    </label>

                    <textarea name="spj_uraian" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        placeholder="Contoh: Pembayaran konsumsi rapat..." required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nominal SPJ
                    </label>

                    <input type="number" name="spj_nominal" min="1"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: 1500000"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal SPJ
                    </label>

                    <input type="date" name="spj_tanggal" class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        File SPJ
                    </label>

                    <input type="file" name="spj_file"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white" required>

                    <p class="text-xs text-slate-500 mt-2">
                        Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX. Maksimal 200 MB.
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-sm text-blue-700">
                    Tanggal input dan identitas operator akan direkam otomatis dari akun login.
                </div>

                <div class="flex justify-end gap-3 pt-5">
                    <button type="button" onclick="closeSPJModal()"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Simpan SPJ
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function openSPJModal(item) {
            let subKegiatan = item.sub_kegiatan ? item.sub_kegiatan.sub_kegiatan_nama : '-';
            let unit = item.unit ? item.unit.unit_nama : '-';

            document.getElementById('modal_spj_subkegiatan').innerText = subKegiatan;
            document.getElementById('modal_spj_unit').innerText = 'Unit Pengampu: ' + unit;

            let action = "{{ url('/user/spj') }}/" + item.spj_pagu_uid + "/store";

            document.getElementById('spjForm').setAttribute('action', action);

            document.getElementById('spjModal').classList.remove('hidden');
            document.getElementById('spjModal').classList.add('flex');
        }

        function closeSPJModal() {
            document.getElementById('spjModal').classList.add('hidden');
            document.getElementById('spjModal').classList.remove('flex');
            document.getElementById('spjForm').reset();
        }

        const cards = Array.from(document.querySelectorAll('.pagu-card'));
        const filterTahun = document.getElementById('filterTahun');
        const filterUnit = document.getElementById('filterUnit');
        const searchPagu = document.getElementById('searchPagu');
        const perPageSelect = document.getElementById('perPage');
        const emptyFilter = document.getElementById('emptyFilter');
        const showingInfo = document.getElementById('showingInfo');
        const paginationInfo = document.getElementById('paginationInfo');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');

        let currentPage = 1;

        function getFilteredCards() {
            const tahun = filterTahun.value;
            const unit = filterUnit.value;
            const keyword = searchPagu.value.toLowerCase().trim();

            return cards.filter(card => {
                const cardTahun = card.dataset.tahun || '';
                const cardUnit = card.dataset.unit || '';
                const cardSearch = card.dataset.search || '';

                const matchTahun = !tahun || cardTahun === tahun;
                const matchUnit = !unit || cardUnit === unit;
                const matchSearch = !keyword || cardSearch.includes(keyword);

                return matchTahun && matchUnit && matchSearch;
            });
        }

        function renderPagination() {
            const filteredCards = getFilteredCards();
            const perPage = parseInt(perPageSelect.value || 10);
            const totalPage = Math.max(1, Math.ceil(filteredCards.length / perPage));

            if (currentPage > totalPage) {
                currentPage = totalPage;
            }

            cards.forEach(card => card.classList.add('hidden'));

            const start = (currentPage - 1) * perPage;
            const end = start + perPage;

            filteredCards.slice(start, end).forEach(card => {
                card.classList.remove('hidden');
            });

            emptyFilter.classList.toggle('hidden', filteredCards.length > 0);

            showingInfo.innerText = filteredCards.length;
            paginationInfo.innerText = `Halaman ${currentPage} dari ${totalPage}`;

            prevPage.disabled = currentPage <= 1;
            nextPage.disabled = currentPage >= totalPage;

            prevPage.classList.toggle('opacity-50', prevPage.disabled);
            nextPage.classList.toggle('opacity-50', nextPage.disabled);
            prevPage.classList.toggle('cursor-not-allowed', prevPage.disabled);
            nextPage.classList.toggle('cursor-not-allowed', nextPage.disabled);
        }

        filterTahun.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        filterUnit.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        searchPagu.addEventListener('input', function() {
            currentPage = 1;
            renderPagination();
        });

        perPageSelect.addEventListener('change', function() {
            currentPage = 1;
            renderPagination();
        });

        prevPage.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPagination();
            }
        });

        nextPage.addEventListener('click', function() {
            const perPage = parseInt(perPageSelect.value || 10);
            const totalPage = Math.max(1, Math.ceil(getFilteredCards().length / perPage));

            if (currentPage < totalPage) {
                currentPage++;
                renderPagination();
            }
        });

        renderPagination();
    </script>

@endsection
