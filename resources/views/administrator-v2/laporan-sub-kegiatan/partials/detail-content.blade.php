@php

    $bulanNama = [
        1 => 'Januari',

        2 => 'Februari',

        3 => 'Maret',

        4 => 'April',

        5 => 'Mei',

        6 => 'Juni',

        7 => 'Juli',

        8 => 'Agustus',

        9 => 'September',

        10 => 'Oktober',

        11 => 'November',

        12 => 'Desember',
    ];

    $totalPersen = 0;

    foreach ($laporan->detail as $detail) {
        $persen = $detail->detail_target > 0 ? ($detail->detail_realisasi / $detail->detail_target) * 100 : 0;

        if ($persen > 100) {
            $persen = 100;
        }

        $totalPersen += $persen;
    }

    $rataCapaian = $laporan->detail->count() > 0 ? $totalPersen / $laporan->detail->count() : 0;

@endphp

<div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">

    <div class="px-7 py-6 border-b border-slate-200 dark:border-slate-800">

        <div class="flex justify-between">

            <div>

                <div class="text-sm text-slate-500">

                    {{ $bulanNama[$laporan->laporan_bulan] }}

                    {{ $laporan->laporan_tahun }}

                </div>

                <h2 class="mt-2 text-2xl font-bold">

                    {{ $laporan->subKegiatan->sub_kegiatan_nama }}

                </h2>

                <div class="mt-2 text-slate-500">

                    Program : {{ $laporan->subKegiatan->kegiatan->program->program_nama }}

                </div>

                <div class="text-slate-500">

                    Kegiatan : {{ $laporan->subKegiatan->kegiatan->kegiatan_nama }}

                </div>

                <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">

                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">

                        <i class="bi bi-person-fill text-blue-600"></i>

                    </div>

                    <div>

                        <div class="text-xs uppercase tracking-wide text-slate-400">

                            Operator

                        </div>

                        <div class="font-medium text-slate-700 dark:text-slate-300">

                            {{ $laporan->laporan_created_by_nama ?? '-' }}

                        </div>

                    </div>

                </div>

                <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">

                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">

                        <i class="bi bi-calendar-event-fill text-blue-600"></i>

                    </div>

                    <div>

                        <div class="text-xs uppercase tracking-wide text-slate-400">

                            Tanggal Membuat Laporan

                        </div>

                        <div class="font-medium text-slate-700 dark:text-slate-300">

                            {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y') }}

                        </div>

                    </div>

                </div>

            </div>

            <div>

                <span class="rounded-xl bg-blue-100 text-blue-700 px-4 py-2">

                    {{ number_format($rataCapaian, 2, ',', '.') }}%

                </span>

            </div>

        </div>

    </div>

    <div class="p-7">
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-100 dark:bg-slate-800">

                    <tr>

                        <th class="px-5 py-4 text-left">

                            No

                        </th>

                        <th class="px-5 py-4 text-left">

                            Indikator

                        </th>

                        <th class="px-5 py-4 text-right">

                            Target

                        </th>

                        <th class="px-5 py-4 text-right">

                            Realisasi

                        </th>

                        <th class="px-5 py-4 text-right">

                            Capaian

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($laporan->detail as $detail)
                        @php

                            $persen =
                                $detail->detail_target > 0
                                    ? ($detail->detail_realisasi / $detail->detail_target) * 100
                                    : 0;

                            if ($persen > 100) {
                                $persen = 100;
                            }

                        @endphp

                        <tr class="border-b border-slate-200 dark:border-slate-800">

                            <td class="px-5 py-4">

                                {{ $loop->iteration }}

                            </td>

                            <td class="px-5 py-4">

                                <div class="font-semibold">

                                    {{ $detail->detail_indikator_nama }}

                                </div>

                            </td>

                            <td class="px-5 py-4 text-right">

                                {{ number_format($detail->detail_target, 2, ',', '.') }}

                                {{ $detail->detail_satuan }}

                            </td>

                            <td class="px-5 py-4 text-right text-green-600 dark:text-green-400">

                                {{ number_format($detail->detail_realisasi, 2, ',', '.') }}

                                {{ $detail->detail_satuan }}

                            </td>

                            <td class="px-5 py-4 text-right">

                                @if ($persen >= 100)
                                    <span
                                        class="inline-flex rounded-full bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 py-1 text-xs font-semibold">

                                        {{ number_format($persen, 2, ',', '.') }}%

                                    </span>
                                @elseif($persen >= 60)
                                    <span
                                        class="inline-flex rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 px-3 py-1 text-xs font-semibold">

                                        {{ number_format($persen, 2, ',', '.') }}%

                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 py-1 text-xs font-semibold">

                                        {{ number_format($persen, 2, ',', '.') }}%

                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="py-12 text-center text-slate-500">

                                Belum ada indikator.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-7">

            <div class="rounded-2xl border border-red-200 dark:border-red-900 bg-red-50 dark:bg-red-900/20 p-5">

                <h3 class="font-bold text-red-700 dark:text-red-300 mb-4">

                    Permasalahan

                </h3>

                <ol class="list-decimal list-inside space-y-2 text-sm">

                    @forelse($laporan->permasalahan as $item)
                        <li>

                            {{ $item->permasalahan_uraian }}

                        </li>

                    @empty

                        <li class="list-none text-slate-500">

                            Tidak ada permasalahan.

                        </li>
                    @endforelse

                </ol>

            </div>

            <div class="rounded-2xl border border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20 p-5">

                <h3 class="font-bold text-blue-700 dark:text-blue-300 mb-4">

                    Solusi

                </h3>

                <ol class="list-decimal list-inside space-y-2 text-sm">

                    @forelse($laporan->solusi as $item)
                        <li>

                            {{ $item->solusi_uraian }}

                        </li>

                    @empty

                        <li class="list-none text-slate-500">

                            Tidak ada solusi.

                        </li>
                    @endforelse

                </ol>

            </div>

            <div class="rounded-2xl border border-green-200 dark:border-green-900 bg-green-50 dark:bg-green-900/20 p-5">

                <h3 class="font-bold text-green-700 dark:text-green-300 mb-4">

                    Tindak Lanjut

                </h3>

                <ol class="list-decimal list-inside space-y-2 text-sm">

                    @forelse($laporan->tindakLanjut as $item)
                        <li>

                            {{ $item->tindak_lanjut_uraian }}

                        </li>

                    @empty

                        <li class="list-none text-slate-500">

                            Tidak ada tindak lanjut.

                        </li>
                    @endforelse

                </ol>

            </div>

        </div>
        <div class="mt-8 flex flex-wrap justify-end gap-3">

            <a href="{{ route('admin.laporan-sub-kegiatan.pdf', $laporan->laporan_uid) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 px-5 py-3 text-white font-semibold">

                <i class="bi bi-file-earmark-pdf"></i>

                PDF

            </a>

            @if ($laporan->laporan_status == 'Aktif')
                <form method="POST"
                    action="{{ route('admin.laporan-sub-kegiatan.nonaktif', $laporan->laporan_uid) }}">

                    @csrf

                    @method('PATCH')

                    <button class="rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3">

                        Nonaktifkan

                    </button>

                </form>
            @else
                <form method="POST" action="{{ route('admin.laporan-sub-kegiatan.aktif', $laporan->laporan_uid) }}">

                    @csrf

                    @method('PATCH')

                    <button class="rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3">

                        Aktifkan

                    </button>

                </form>
            @endif

            <button onclick="openCatatan('{{ $laporan->laporan_uid }}')"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-5 py-3 text-white font-semibold">

                <i class="bi bi-chat-left-text"></i>

                Catatan

            </button>

        </div>

    </div>

</div>
