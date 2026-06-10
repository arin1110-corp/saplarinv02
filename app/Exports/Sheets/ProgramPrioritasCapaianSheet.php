<?php

namespace App\Exports\Sheets;

use App\Models\ModelProgramPrioritas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProgramPrioritasCapaianSheet implements FromArray, WithEvents, WithTitle
{
    public function array(): array
    {
        $rows = [];

        $rows[] = ['DETAIL CAPAIAN PROGRAM PRIORITAS'];
        $rows[] = ['DINAS KEBUDAYAAN PROVINSI BALI'];
        $rows[] = ['Dicetak: ' . date('d/m/Y H:i:s')];
        $rows[] = [];

        $rows[] = [
            'No',
            'Tahun',
            'Program Prioritas',
            'Rencana Aksi',
            'Target Rencana',
            'Judul Capaian',
            'Jumlah Capaian',
            'Persentase Capaian',
            'Deskripsi Capaian',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Operator',
            'NIP Operator',
            'Bidang Operator',
            'Jumlah File Bukti',
            'Status Capaian',
        ];

        $data = ModelProgramPrioritas::with('rencana.capaian.files')
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $no = 1;

        foreach ($data as $item) {
            foreach ($item->rencana as $rencana) {
                $target = (int) $rencana->rencana_target;

                foreach ($rencana->capaian as $capaian) {
                    $jumlah = (int) ($capaian->capaian_jumlah ?? 1);

                    $persen = $target > 0 ? ($jumlah / $target) * 100 : 0;
                    $persen = min($persen, 100);

                    $rows[] = [
                        $no++,
                        $item->prioritas_tahun,
                        $item->prioritas_judul,
                        $rencana->rencana_judul,
                        $target,
                        $capaian->capaian_judul,
                        $jumlah,
                        number_format($persen, 2, ',', '.') . '%',
                        $capaian->capaian_deskripsi,
                        $capaian->capaian_tanggal_mulai ? $capaian->capaian_tanggal_mulai->format('d/m/Y') : '-',
                        $capaian->capaian_tanggal_selesai ? $capaian->capaian_tanggal_selesai->format('d/m/Y') : '-',
                        $capaian->capaian_user_nama,
                        $capaian->capaian_user_nip,
                        $capaian->capaian_bidang_nama,
                        $capaian->files->count(),
                        $capaian->capaian_status,
                    ];
                }
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Detail Capaian';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:P1');
                $sheet->mergeCells('A2:P2');
                $sheet->mergeCells('A3:P3');

                $sheet->getStyle('A1:P3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:P3')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(16);

                $sheet->getStyle('A5:P5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '7C3AED'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A5:P{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                ]);

                foreach (range('A', 'P') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->getStyle("A6:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E6:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("J6:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("O6:P{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->freezePane('A6');
            },
        ];
    }
}