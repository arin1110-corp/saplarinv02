<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan PWA</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 30px;
            margin: auto;
            page-break-after: always;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            margin-right: 20px;
        }

        .title {
            text-align: center;
            flex: 1;
        }

        .title h2 {
            margin: 0;
            font-size: 20px;
        }

        .title h3 {
            margin: 0;
            font-size: 16px;
        }

        .title p {
            margin: 0;
            font-size: 14px;
        }

        .tahun {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        table th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <!-- HALAMAN 1 -->
    <div class="page">

        <div class="header">

            <img src="{{ public_path('logo.png') }}" class="logo">

            <div class="title">
                <h2>Penggunaan Dana Pungutan Wisatawan Asing (PWA)</h2>
                <h3>Dinas Kebudayaan Provinsi Bali</h3>
            </div>

        </div>

        <div class="tahun">
            TAHUN 2024
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Sub Kegiatan</th>
                    <th>Uraian</th>
                    <th>Jumlah Dana</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($data2024 as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->sub_kegiatan }}</td>
                        <td>{{ $row->uraian }}</td>
                        <td>{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $row->keterangan }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>


    <!-- HALAMAN 2 -->
    <div class="page">

        <div class="header">

            <img src="{{ public_path('logo.png') }}" class="logo">

            <div class="title">
                <h2>Penggunaan Dana Pungutan Wisatawan Asing (PWA)</h2>
                <h3>Dinas Kebudayaan Provinsi Bali</h3>
            </div>

        </div>

        <div class="tahun">
            TAHUN 2025
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Sub Kegiatan</th>
                    <th>Uraian</th>
                    <th>Jumlah Dana</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($data2025 as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->sub_kegiatan }}</td>
                        <td>{{ $row->uraian }}</td>
                        <td>{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $row->keterangan }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

</body>

</html>
