<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 5px;
        }

        .judul {
            width: 200px;
            font-weight: bold;
        }

        .titik {
            width: 10px;
            text-align: center;
        }

        ol {
            margin: 0;
            padding-left: 18px;
        }

        p {
            margin: 0;
        }

        .merah {
            color: red;
        }
    </style>
</head>

<body>

    @php
        $detail = $laporan->detail->first();
    @endphp

    <table>

        <tr>
            <td class="judul">PROGRAM</td>
            <td class="titik">:</td>
            <td>
                {{ $laporan->subKegiatan->kegiatan->program->program_nama ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="judul">KEGIATAN</td>
            <td class="titik">:</td>
            <td>
                {{ $laporan->subKegiatan->kegiatan->kegiatan_nama ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="judul">SUB KEGIATAN</td>
            <td class="titik">:</td>
            <td>
                {{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="judul">INDIKATOR</td>
            <td class="titik">:</td>
            <td>
                {{ $detail->detail_indikator_nama ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="judul">TARGET KINERJA</td>
            <td class="titik">:</td>
            <td>
                {{ $detail->detail_target ?? 0 }}
                {{ $detail->detail_satuan ?? '' }}
            </td>
        </tr>

        <tr>
            <td class="judul">REALISASI KINERJA</td>
            <td class="titik">:</td>
            <td>
                {{ $detail->detail_realisasi ?? 0 }}
                {{ $detail->detail_satuan ?? '' }}
            </td>
        </tr>

        <tr>
            <td class="judul">PERMASALAHAN</td>
            <td class="titik">:</td>
            <td>

                <ol>
                    @forelse($laporan->permasalahan as $item)
                        <li>{{ $item->permasalahan_uraian }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ol>

            </td>
        </tr>

        <tr>
            <td class="judul">SOLUSI</td>
            <td class="titik">:</td>
            <td>

                <ol>
                    @forelse($laporan->solusi as $item)
                        <li>{{ $item->solusi_uraian }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ol>

            </td>
        </tr>

        <tr>
            <td class="judul">TINDAK LANJUT</td>
            <td class="titik">:</td>
            <td>

                <ol>
                    @forelse($laporan->tindakLanjut as $item)
                        <li>{{ $item->tindak_lanjut_uraian }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ol>

            </td>
        </tr>

        @if ($laporan->laporan_catatan_admin)
            <tr>
                <td class="judul">CATATAN ADMIN</td>
                <td class="titik">:</td>
                <td>
                    {{ $laporan->laporan_catatan_admin }}
                </td>
            </tr>
        @endif

    </table>

</body>
</html>