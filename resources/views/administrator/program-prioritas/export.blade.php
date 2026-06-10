<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 16px;">
                LAPORAN KINERJA PRIORITAS
            </th>
        </tr>
        <tr>
            <th colspan="6">
                Dicetak: {{ date('d/m/Y H:i:s') }}
            </th>
        </tr>
    </thead>
</table>

<br>

<table>
    <thead>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Tahun</th>
            <th style="font-weight: bold;">Prioritas</th>
            <th style="font-weight: bold;">Target</th>
            <th style="font-weight: bold;">Capaian Aktif</th>
            <th style="font-weight: bold;">Persentase</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($prioritas as $item)
            @php
                $targetPrioritas = $item->rencana->sum(function ($r) {
                    return (int) $r->rencana_target;
                });

                $capaianPrioritas = $item->rencana->sum(function ($r) {
                    return $r->capaian
                        ->where('capaian_status', 'Aktif')
                        ->sum('capaian_jumlah');
                });

                $persenPrioritas = $targetPrioritas > 0
                    ? ($capaianPrioritas / $targetPrioritas) * 100
                    : 0;

                if ($persenPrioritas > 100) {
                    $persenPrioritas = 100;
                }
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->prioritas_tahun }}</td>
                <td>{{ $item->prioritas_judul }}</td>
                <td>{{ $targetPrioritas }}</td>
                <td>{{ $capaianPrioritas }}</td>
                <td>{{ number_format($persenPrioritas, 2, ',', '.') }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<br>

<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold;">
                DETAIL RENCANA AKSI
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Tahun</th>
            <th style="font-weight: bold;">Prioritas</th>
            <th style="font-weight: bold;">Rencana Aksi</th>
            <th style="font-weight: bold;">Target</th>
            <th style="font-weight: bold;">Capaian Aktif</th>
            <th style="font-weight: bold;">Persentase</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>

    <tbody>
        @php
            $noRencana = 1;
        @endphp

        @foreach ($prioritas as $item)
            @foreach ($item->rencana as $rencana)
                @php
                    $targetRencana = (int) $rencana->rencana_target;

                    $capaianRencana = $rencana->capaian
                        ->where('capaian_status', 'Aktif')
                        ->sum('capaian_jumlah');

                    $persenRencana = $targetRencana > 0
                        ? ($capaianRencana / $targetRencana) * 100
                        : 0;

                    if ($persenRencana > 100) {
                        $persenRencana = 100;
                    }
                @endphp

                <tr>
                    <td>{{ $noRencana++ }}</td>
                    <td>{{ $item->prioritas_tahun }}</td>
                    <td>{{ $item->prioritas_judul }}</td>
                    <td>{{ $rencana->rencana_judul }}</td>
                    <td>{{ $targetRencana }}</td>
                    <td>{{ $capaianRencana }}</td>
                    <td>{{ number_format($persenRencana, 2, ',', '.') }}%</td>
                    <td>{{ $rencana->rencana_status }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<br>
<br>

<table>
    <thead>
        <tr>
            <th colspan="13" style="font-weight: bold;">
                DETAIL CAPAIAN
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold;">No</th>
            <th style="font-weight: bold;">Tahun</th>
            <th style="font-weight: bold;">Prioritas</th>
            <th style="font-weight: bold;">Rencana Aksi</th>
            <th style="font-weight: bold;">Judul Capaian</th>
            <th style="font-weight: bold;">Jumlah Capaian</th>
            <th style="font-weight: bold;">Persentase</th>
            <th style="font-weight: bold;">Deskripsi</th>
            <th style="font-weight: bold;">Tanggal Mulai</th>
            <th style="font-weight: bold;">Tanggal Selesai</th>
            <th style="font-weight: bold;">Bidang</th>
            <th style="font-weight: bold;">Operator</th>
            <th style="font-weight: bold;">Status</th>
        </tr>
    </thead>

    <tbody>
        @php
            $noCapaian = 1;
        @endphp

        @foreach ($prioritas as $item)
            @foreach ($item->rencana as $rencana)
                @php
                    $targetRencana = (int) $rencana->rencana_target;
                @endphp

                @foreach ($rencana->capaian as $capaian)
                    @php
                        $jumlahCapaian = (int) ($capaian->capaian_jumlah ?? 1);

                        $persenCapaian = $targetRencana > 0
                            ? ($jumlahCapaian / $targetRencana) * 100
                            : 0;

                        if ($persenCapaian > 100) {
                            $persenCapaian = 100;
                        }
                    @endphp

                    <tr>
                        <td>{{ $noCapaian++ }}</td>
                        <td>{{ $item->prioritas_tahun }}</td>
                        <td>{{ $item->prioritas_judul }}</td>
                        <td>{{ $rencana->rencana_judul }}</td>
                        <td>{{ $capaian->capaian_judul }}</td>
                        <td>{{ $jumlahCapaian }}</td>
                        <td>{{ number_format($persenCapaian, 2, ',', '.') }}%</td>
                        <td>{{ $capaian->capaian_deskripsi }}</td>
                        <td>{{ $capaian->capaian_tanggal_mulai ? $capaian->capaian_tanggal_mulai->format('d/m/Y') : '-' }}</td>
                        <td>{{ $capaian->capaian_tanggal_selesai ? $capaian->capaian_tanggal_selesai->format('d/m/Y') : '-' }}</td>
                        <td>{{ $capaian->capaian_bidang_nama ?? '-' }}</td>
                        <td>{{ $capaian->capaian_user_nama ?? '-' }}</td>
                        <td>{{ $capaian->capaian_status }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>