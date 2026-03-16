<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan PWA</title>

    <style>
        body {
            font-family: "Segoe UI", Arial;
            background: #f4f6f9;
            margin: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .logo {
            width: 200px;
        }

        .judul {
            font-size: 24px;
            font-weight: 700;
        }

        .subjudul {
            color: #555;
        }

        .tahun-box {
            margin-top: 40px;
            padding: 12px;
            background: #2c3e50;
            color: white;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px;
        }

        .card {
            border: 1px solid #ddd;
            border-left: 6px solid #3498db;
            background: #fafafa;
            padding: 15px;
            margin-top: 20px;
        }

        .subkegiatan {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .info {
            font-size: 14px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background: #34495e;
            color: white;
            padding: 8px;
            font-size: 13px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 13px;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .total {
            font-weight: bold;
            background: #ecf0f1;
        }

        .btn-file {
            background: #3498db;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
        }

        .btn-file:hover {
            background: #2980b9;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">

            <img src="{{ asset('image/pemprov.png') }}" class="logo" alt="Logo Pemprov Bali">

            <div class="judul">
                LAPORAN PENGGUNAAN PUNGUTAN WISATAWAN ASING (PWA)
            </div>

            <div class="subjudul">
                DINAS KEBUDAYAAN PROVINSI BALI
            </div>

        </div>

        {{-- LOOP TAHUN --}}
        @foreach ($data as $tahun => $subs)
            <div class="tahun-box">
                TAHUN {{ $tahun }}
            </div>

            {{-- LOOP SUB KEGIATAN --}}
            @foreach ($subs as $sub)
                <div class="card">

                    <div class="subkegiatan">
                        {{ $sub->subkegiatan_pwa_nama }}
                    </div>

                    <div class="info">
                        Pagu : <b>Rp {{ number_format($sub->data_pwa_pagu, 0, ',', '.') }}</b>
                    </div>

                    <div class="info">
                        Realisasi Temuan :
                        <b>Rp {{ number_format($sub->data_pwa_realisasi ?? 0, 0, ',', '.') }}</b>
                    </div>

                    <table>

                        <tr>
                            <th width="50">No</th>
                            <th>Keterangan</th>
                            <th width="160">Nominal</th>
                            <th width="120">File</th>
                        </tr>

                        @forelse($sub->laporan as $i => $lap)
                            <tr>

                                <td align="center">
                                    {{ $i + 1 }}
                                </td>

                                <td>
                                    {{ $lap->laporan_pwa_keterangan }}
                                </td>

                                <td align="right">
                                    Rp {{ number_format($lap->laporan_pwa_nominal, 0, ',', '.') }}
                                </td>

                                <td align="center">

                                    @if ($lap->laporan_pwa_file)
                                        <a class="btn-file" href="{{ asset($lap->laporan_pwa_file) }}"
                                            target="_blank">
                                            Lihat File
                                        </a>
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" align="center">
                                    Belum ada laporan
                                </td>
                            </tr>
                        @endforelse

                        <tr class="total">

                            <td colspan="2" align="center">
                                TOTAL
                            </td>

                            <td align="right">
                                Rp {{ number_format($sub->total, 0, ',', '.') }}
                            </td>

                            <td></td>

                        </tr>

                    </table>

                </div>
            @endforeach
        @endforeach

    </div>

</body>

</html>
