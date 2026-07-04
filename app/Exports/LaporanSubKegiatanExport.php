<?php

namespace App\Exports;

use App\Models\SubKegiatanLaporan;
use App\Models\SubKegiatanIndikator;
use App\Models\ModelSubKegiatan;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanSubKegiatanExport implements WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:H1');
                $sheet->mergeCells('I1:I2');
                $sheet->mergeCells('J1:J2');
                $sheet->mergeCells('K1:K2');
                $sheet->mergeCells('L1:L2');
                $sheet->mergeCells('M1:M2');
                $sheet->mergeCells('N1:N2');

                $sheet->setCellValue('A1', 'No');

                $sheet->setCellValue('B1', 'Uraian Program/Kegiatan/Sub Kegiatan');

                $sheet->setCellValue('C1', 'Indikator Kinerja');

                $sheet->setCellValue('D1', 'Target Kinerja');

                $sheet->setCellValue('E1', 'Realisasi');

                $sheet->setCellValue('E2', 'TW I');

                $sheet->setCellValue('F2', 'TW II');

                $sheet->setCellValue('G2', 'TW III');

                $sheet->setCellValue('H2', 'TW IV');

                $sheet->setCellValue('I1', 'Satuan');

                $sheet->setCellValue('J1', 'Operator');

                $sheet->setCellValue('K1', 'Tanggal Input');

                $sheet->setCellValue('L1', 'Permasalahan');

                $sheet->setCellValue('M1', 'Solusi');

                $sheet->setCellValue('N1', 'Tindak Lanjut');

                $sheet->setCellValue('O1', 'Catatan Admin');

                $row = 3;
                $no = 1;

                $data = SubKegiatanIndikator::with(['subKegiatan.kegiatan.program', 'subKegiatan.laporan.detail', 'subKegiatan.laporan.permasalahan', 'subKegiatan.laporan.solusi', 'subKegiatan.laporan.tindakLanjut'])
                    ->orderBy('indikator_unit_kode')
                    ->orderBy('indikator_sub_kegiatan_id')
                    ->get();

                $lastUnit = '';
                $lastProgram = '';
                $lastKegiatan = '';

                foreach ($data as $indikator) {
                    $sub = $indikator->subKegiatan;

                    if (!$sub) {
                        continue;
                    }

                    $kegiatan = $sub->kegiatan;
                    $program = $kegiatan?->program;

                    $unitKode = $indikator->indikator_unit_kode ?? '-';
                    $unitNama = $indikator->indikator_unit_nama ?? 'TANPA UNIT';

                    /*
    |--------------------------------------------------------------------------
    | UNIT
    |--------------------------------------------------------------------------
    */
                    if ($lastUnit != $unitKode) {
                        $sheet->mergeCells("A{$row}:O{$row}");

                        $sheet->setCellValue("A{$row}", $unitKode . ' - ' . strtoupper($unitNama));

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FFFF00');

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFont()
                            ->setBold(true);

                        $row++;

                        $lastUnit = $unitKode;
                        $lastProgram = '';
                        $lastKegiatan = '';
                    }

                    /*
    |--------------------------------------------------------------------------
    | PROGRAM
    |--------------------------------------------------------------------------
    */
                    $programId = $program?->program_id;
                    $programNama = $program?->program_nama;

                    if ($lastProgram != $programId) {
                        $sheet->setCellValue("A{$row}", 'PROGRAM');
                        $sheet->setCellValue("B{$row}", $programNama);

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('F4B183');

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFont()
                            ->setBold(true);

                        $row++;

                        $lastProgram = $programId;
                        $lastKegiatan = '';
                    }

                    /*
    |--------------------------------------------------------------------------
    | KEGIATAN
    |--------------------------------------------------------------------------
    */
                    $kegiatanId = $kegiatan?->kegiatan_id;
                    $kegiatanNama = $kegiatan?->kegiatan_nama;

                    if ($lastKegiatan != $kegiatanId) {
                        $sheet->setCellValue("A{$row}", 'KEGIATAN');
                        $sheet->setCellValue("B{$row}", $kegiatanNama);

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('D9EAD3');

                        $sheet
                            ->getStyle("A{$row}:O{$row}")
                            ->getFont()
                            ->setBold(true);

                        $row++;

                        $lastKegiatan = $kegiatanId;
                    }

                    /*
    |--------------------------------------------------------------------------
    | LAPORAN PER UNIT
    |--------------------------------------------------------------------------
    */
                    $item = $sub->laporan->where('laporan_unit_kode', $unitKode)->sortByDesc('laporan_tahun')->first();

                    // lanjutkan pakai kode BELUM INPUT dan SUDAH INPUT milik Anda,
                    // tidak perlu diubah lagi.

                    /*
    |--------------------------------------------------------------------------
    | BELUM INPUT
    |--------------------------------------------------------------------------
    */
                    if (!$item) {
                        $sheet->setCellValue('A' . $row, $no++);

                        $sheet->setCellValue('B' . $row, $sub->sub_kegiatan_nama);

                        $indikatorSub = $sub->indikator->where('indikator_unit_kode', $unitKode);

                        $sheet->setCellValue('C' . $row, $indikatorSub->pluck('indikator_nama')->unique()->implode("\n"));

                        $sheet->setCellValue('D' . $row, $indikatorSub->sum('indikator_target'));

                        $sheet->setCellValue('E' . $row, 0);

                        $sheet->setCellValue('F' . $row, 0);

                        $sheet->setCellValue('G' . $row, 0);

                        $sheet->setCellValue('H' . $row, 0);

                        $sheet->setCellValue('I' . $row, $indikatorSub->pluck('indikator_satuan')->implode("\n"));

                        $sheet->setCellValue(
                            'J' . $row,
                            'Belum diinput'
                        );

                        $sheet->setCellValue(
                            'K' . $row,
                            'Belum diinput'
                        );

                        $sheet->setCellValue('L' . $row, 'Belum diinput');

                        $sheet->setCellValue('M' . $row, 'Belum diinput');

                        $sheet->setCellValue('N' . $row, 'Belum diinput');

                        $sheet->setCellValue('O' . $row, '-');

                        $row++;

                        continue;
                    }

                    /*
    |--------------------------------------------------------------------------
    | SUDAH INPUT
    |--------------------------------------------------------------------------
    */

                    $tw1 = 0;
                    $tw2 = 0;
                    $tw3 = 0;
                    $tw4 = 0;

                    foreach ($item->detail as $detail) {
                        $bulan = $item->laporan_bulan;

                        if ($bulan <= 3) {
                            $tw1 += $detail->detail_realisasi;
                        } elseif ($bulan <= 6) {
                            $tw2 += $detail->detail_realisasi;
                        } elseif ($bulan <= 9) {
                            $tw3 += $detail->detail_realisasi;
                        } else {
                            $tw4 += $detail->detail_realisasi;
                        }
                    }

                    $sheet->setCellValue('A' . $row, $no++);

                    $sheet->setCellValue('B' . $row, $sub->sub_kegiatan_nama);

                    $sheet->setCellValue('C' . $row, $item->detail->pluck('detail_indikator_nama')->implode("\n"));

                    $sheet->setCellValue('D' . $row, $item->detail->sum('detail_target'));

                    $sheet->setCellValue('E' . $row, $tw1);

                    $sheet->setCellValue('F' . $row, $tw2);

                    $sheet->setCellValue('G' . $row, $tw3);

                    $sheet->setCellValue('H' . $row, $tw4);

                    $sheet->setCellValue('I' . $row, $item->detail->pluck('detail_satuan')->implode("\n"));

                    $sheet->setCellValue('J' . $row, $item->laporan_created_by_nama);

                    $sheet->setCellValue('K' . $row, optional($item->created_at)->format('d-m-Y'));

                    $sheet->setCellValue('L' . $row, $item->permasalahan->pluck('permasalahan_uraian')->implode("\n"));

                    $sheet->setCellValue('M' . $row, $item->solusi->pluck('solusi_uraian')->implode("\n"));

                    $sheet->setCellValue('N' . $row, $item->tindakLanjut->pluck('tindak_lanjut_uraian')->implode("\n"));

                    $sheet->setCellValue('O' . $row, $item->laporan_catatan_admin);

                    $row++;
                }
            },
        ];
    }
}