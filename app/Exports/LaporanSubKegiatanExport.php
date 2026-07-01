<?php

namespace App\Exports;

use App\Models\SubKegiatanLaporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanSubKegiatanExport
implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SubKegiatanLaporan::select(
            'laporan_tahun',
            'laporan_bulan',
            'laporan_unit_nama',
            'laporan_created_by_nama',
            'laporan_status'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Tahun',
            'Bulan',
            'Unit',
            'Operator',
            'Status',
        ];
    }
}