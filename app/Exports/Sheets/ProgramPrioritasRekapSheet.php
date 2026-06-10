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

class ProgramPrioritasRekapSheet implements FromArray, WithEvents, WithTitle
{
    public function array(): array
    {
        $rows = [];

        $rows[] = ['REKAP PROGRAM PRIORITAS'];
        $rows[] = ['DINAS KEBUDAYAAN PROVINSI BALI'];
        $rows[] = ['Dicetak: ' . date('d/m/Y H:i:s')];
        $rows[] = [];

        $rows[] = [
            'No',
            'Tahun',
            'Program Prioritas',
            'Deskripsi',
            'Jumlah Rencana Aksi',
            'Total Target',
            'Total Capaian Aktif',
            'Persentase',
            'Status',
        ];

        $data = ModelProgramPrioritas::with('rencana.capaian')
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $no = 1;

        foreach ($data as $item) {
            $totalTarget = $item->rencana->sum(function ($r) {
                return (int) $r->rencana_target;
            });

            $totalCapaian = $item->rencana->sum(function ($r) {
                return $r->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');
            });

            $persen = $totalTarget > 0 ? ($totalCapaian / $totalTarget) * 100 : 0;
            $persen = min($persen, 100);

            $rows[] = [
                $no++,
                $item->prioritas_tahun,
                $item->prioritas_judul,
                $item->prioritas_deskripsi,
                $item->rencana->count(),
                $totalTarget,
                $totalCapaian,
                number_format($persen, 2, ',', '.') . '%',
                $item->prioritas_status,
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Rekap Prioritas';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->styleSheet($event, 'A1:I1', 'A5:I5', 'A5:I');
            },
        ];
    }

    private function styleSheet($event, $titleRange, $headerRange, $tablePrefix)
    {
        $sheet = $event->sheet->getDelegate();
        $highestRow = $sheet->getHighestRow();

        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

        $sheet->getStyle('A1:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I3')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A5:I{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
        ]);

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle("A6:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B6:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E6:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A6');
    }
}