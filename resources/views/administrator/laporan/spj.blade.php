@extends('administrator.layouts.app')

@section('title','Laporan SPJ')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold">
            Dashboard Laporan SPJ
        </h1>

        <p class="text-slate-400">
            Monitoring realisasi anggaran SPJ.
        </p>
    </div>

    <div class="bg-slate-900 rounded-2xl p-6">

        <h2 class="font-bold text-lg mb-5">
            Realisasi SPJ per Unit
        </h2>

        <div style="height:500px">

            <canvas id="unitChart"></canvas>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const unitLabels = [
    @foreach($chartUnit as $item)
        "{{ $item->spj_bidang_nama }}",
    @endforeach
];

const unitData = [
    @foreach($chartUnit as $item)
        {{ $item->total }},
    @endforeach
];

new Chart(document.getElementById('unitChart'), {

    type: 'pie',

    data: {

        labels: unitLabels,

        datasets: [{
            data: unitData
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false
    }

});

</script>

@endsection