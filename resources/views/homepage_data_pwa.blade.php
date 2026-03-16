<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Input Data PWA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
        }

        .card-form {
            max-width: 700px;
            margin: auto;
            margin-top: 50px;
        }

        .logo {
            width: 80px;
        }

        .judul {
            font-weight: 600;
            font-size: 22px;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="card shadow card-form">

            <div class="card-body">

                <div class="text-center mb-4">

                    <img src="/image/pemprov.png" class="logo">

                    <div class="judul mt-2">
                        INPUT DATA PWA
                    </div>

                    <div class="text-muted">
                        Sistem Laporan Anggaran
                    </div>

                </div>


                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('data.pwa.store') }}">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Sub Kegiatan
                        </label>

                        <select name="subkegiatan" class="form-select" required>

                            <option value="">-- Pilih Sub Kegiatan --</option>

                            @foreach ($subkegiatan as $sub)
                                <option value="{{ $sub->subkegiatan_pwa_id }}">
                                    {{ $sub->subkegiatan_pwa_nama }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Tahun
                        </label>

                        <input type="number" name="tahun" class="form-control" placeholder="2024" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Pagu
                        </label>

                        <input type="text" name="pagu" class="form-control" placeholder="50000000" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Realisasi (Tabel BPKAD)
                        </label>

                        <input type="text" name="realisasi" class="form-control" placeholder="50000000" required>
                    </div>

                    <div class="d-grid">

                        <button class="btn btn-primary">
                            Simpan Data
                        </button>

                    </div>

                </form>
                <!-- TABEL DATA -->
                <br>
                <h5 class="mb-3">Data PWA</h5>

                <div class="table-responsive mb-4">

                    <table class="table table-bordered table-striped">

                        <thead class="table-dark">

                            <tr>
                                <th width="30%">Sub Kegiatan</th>
                                <th width="10%">Tahun</th>
                                <th width="30%">Pagu</th>
                                <th width="30%">Realisasi</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($dataPwa as $data)
                                <tr>

                                    <td>
                                        {{ $data->subkegiatan->subkegiatan_pwa_nama }}
                                    </td>

                                    <td>
                                        {{ $data->data_pwa_tahun }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($data->data_pwa_pagu, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        Rp {{ number_format($data->data_pwa_realisasi, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center">
                                        Belum ada data
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>
                <form action="/import-laporan-pwa" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label>Upload Excel</label>
                        <input type="file" name="file" required>
                    </div>

                    <button type="submit">
                        Import Data
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>
