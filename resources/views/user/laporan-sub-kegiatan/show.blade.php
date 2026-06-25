@extends('layouts.user')

@section('content')
<div class="container-fluid py-4">

    <h4 class="mb-3">Detail Laporan Sub Kegiatan</h4>

    <div class="card mb-3">
        <div class="card-header">Informasi Laporan</div>
        <div class="card-body">
            <p><strong>Sub Kegiatan:</strong> {{ $laporan->subKegiatan->sub_kegiatan_nama ?? '-' }}</p>
            <p><strong>Bulan:</strong> {{ $laporan->bulan }}</p>
            <p><strong>Tahun:</strong> {{ $laporan->tahun }}</p>
            <p><strong>Catatan:</strong> {{ $laporan->catatan ?? '-' }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Realisasi Indikator</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th width="120">Target</th>
                        <th width="120">Realisasi</th>
                        <th width="120">Satuan</th>
                        <th width="120">Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan->detail as $detail)
                        @php
                            $target = $detail->indikator->target ?? 0;
                            $realisasi = $detail->realisasi ?? 0;
                            $capaian = $target > 0 ? ($realisasi / $target) * 100 : 0;
                        @endphp
                        <tr>
                            <td>{{ $detail->indikator->indikator ?? '-' }}</td>
                            <td>{{ rtrim(rtrim(number_format($target, 2, ',', '.'), '0'), ',') }}</td>
                            <td>{{ rtrim(rtrim(number_format($realisasi, 2, ',', '.'), '0'), ',') }}</td>
                            <td>{{ $detail->indikator->satuan ?? '-' }}</td>
                            <td>{{ number_format($capaian, 2, ',', '.') }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Permasalahan</div>
        <div class="card-body">
            <ol>
                @forelse($laporan->permasalahan as $item)
                    <li>{{ $item->uraian }}</li>
                @empty
                    <li>Tidak ada permasalahan.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Solusi</div>
        <div class="card-body">
            <ol>
                @forelse($laporan->solusi as $item)
                    <li>{{ $item->uraian }}</li>
                @empty
                    <li>Tidak ada solusi.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Tindak Lanjut</div>
        <div class="card-body">
            <ol>
                @forelse($laporan->tindakLanjut as $item)
                    <li>{{ $item->uraian }}</li>
                @empty
                    <li>Tidak ada tindak lanjut.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <a href="{{ route('user.laporan-sub-kegiatan.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>
@endsection