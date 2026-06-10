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

class ProgramPrioritasRencanaSheet implements FromArray, WithEvents, WithTitle
{
    public function array(): array
    {
        $rows = [];

        $rows[] = ['DETAIL RENCANA AKSI PROGRAM PRIORITAS'];
        $rows[] = ['DINAS KEBUDAYAAN PROVINSI BALI'];
        $rows[] = ['Dicetak: ' . date('d/m/Y H:i:s')];
        $rows[] = [];

        $rows[] = [
            'No',
            'Tahun',
            'Program Prioritas',
            'Rencana Aksi',
            'Target',
            'Capaian Aktif',
            'Persentase',
            'Operator Input',
            'NIP Operator',
            'Bidang Operator',
            'Status Rencana',
        ];

        $data = ModelProgramPrioritas::with('rencana.capaian')
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $no = 1;

        foreach ($data as $item) {
            foreach ($item->rencana as $rencana) {
                $target = (int) $rencana->rencana_target;

                $capaian = $rencana->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');

                $persen = $target > 0 ? ($capaian / $target) * 100 : 0;
                $persen = min($persen, 100);

                $rows[] = [
                    $no++,
                    $item->prioritas_tahun,
                    $item->prioritas_judul,
                    $rencana->rencana_judul,
                    $target,
                    $capaian,
                    number_format($persen, 2, ',', '.') . '%',
                    $rencana->rencana_user_nama,
                    $rencana->rencana_user_nip,
                    $rencana->rencana_bidang_nama,
                    $rencana->rencana_status,
                ];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Rencana Aksi';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('A3:K3');

                $sheet->getStyle('A1:K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:K3')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(16);

                $sheet->getStyle('A5:K5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '15803D'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A5:K{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                ]);

                foreach (range('A', 'K') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->getStyle("A6:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E6:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->freezePane('A6');
            },
        ];
    }
}