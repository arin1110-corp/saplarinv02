<?php

namespace App\Exports;

use App\Models\ModelProgramPrioritas;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProgramPrioritasExport implements FromArray, WithEvents, WithTitle
{
    public function array(): array
    {
        $rows = [];

        $rows[] = ['LAPORAN KINERJA PRIORITAS'];
        $rows[] = ['DINAS KEBUDAYAAN PROVINSI BALI'];
        $rows[] = ['Dicetak: ' . date('d/m/Y H:i:s')];
        $rows[] = [];

        $data = ModelProgramPrioritas::with([
            'rencana.capaian.files',
        ])
            ->orderBy('prioritas_tahun', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($data as $prioritas) {
            $rows[] = ['Tahun', $prioritas->prioritas_tahun];
            $rows[] = ['Program Prioritas', $prioritas->prioritas_judul];
            $rows[] = ['Deskripsi', $prioritas->prioritas_deskripsi ?: '-'];
            $rows[] = ['Status', $prioritas->prioritas_status];
            $rows[] = [];

            foreach ($prioritas->rencana as $indexRencana => $rencana) {
                $target = (int) $rencana->rencana_target;

                $totalCapaian = $rencana->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');

                $persenRencana = $target > 0 ? ($totalCapaian / $target) * 100 : 0;
                $persenRencana = min($persenRencana, 100);

                $rows[] = [
                    'Rencana Aksi ' . ($indexRencana + 1),
                    $rencana->rencana_judul,
                    'Target',
                    $target,
                    'Capaian',
                    $totalCapaian,
                    'Persentase',
                    number_format($persenRencana, 2, ',', '.') . '%',
                    '',
                    '',
                    '',
                ];

                $rows[] = [
                    '',
                    'No',
                    'Judul Capaian',
                    'Jumlah',
                    'Persentase',
                    'Tanggal',
                    'Operator',
                    'Bidang',
                    'Status',
                    'File Bukti',
                    'Deskripsi',
                ];

                if ($rencana->capaian->count() > 0) {
                    foreach ($rencana->capaian as $indexCapaian => $capaian) {
                        $jumlah = (int) ($capaian->capaian_jumlah ?? 1);

                        $persenCapaian = $target > 0 ? ($jumlah / $target) * 100 : 0;
                        $persenCapaian = min($persenCapaian, 100);

                        $tanggalMulai = $capaian->capaian_tanggal_mulai
                            ? $capaian->capaian_tanggal_mulai->format('d/m/Y')
                            : '-';

                        $tanggalSelesai = $capaian->capaian_tanggal_selesai
                            ? $capaian->capaian_tanggal_selesai->format('d/m/Y')
                            : '-';

                        $rows[] = [
                            '',
                            $indexCapaian + 1,
                            $capaian->capaian_judul,
                            $jumlah,
                            number_format($persenCapaian, 2, ',', '.') . '%',
                            $tanggalMulai . ' - ' . $tanggalSelesai,
                            $capaian->capaian_user_nama ?? '-',
                            $capaian->capaian_bidang_nama ?? '-',
                            $capaian->capaian_status,
                            $capaian->files->count(),
                            $capaian->capaian_deskripsi,
                        ];
                    }
                } else {
                    $rows[] = [
                        '',
                        '-',
                        'Belum ada capaian',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ];
                }

                $rows[] = [];
            }

            $rows[] = [];
            $rows[] = [];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Kinerja Prioritas';
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

                $sheet->getStyle('A1:K3')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(16);
                $sheet->getStyle('A1:K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Lebar kolom dibuat tetap supaya teks panjang tidak bikin Excel melebar.
                $sheet->getColumnDimension('A')->setWidth(20);
                $sheet->getColumnDimension('B')->setWidth(100);
                $sheet->getColumnDimension('C')->setWidth(38);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(24);
                $sheet->getColumnDimension('G')->setWidth(24);
                $sheet->getColumnDimension('H')->setWidth(24);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(12);
                $sheet->getColumnDimension('K')->setWidth(55);

                for ($row = 1; $row <= $highestRow; $row++) {
                    $valueA = $sheet->getCell('A' . $row)->getValue();
                    $valueB = $sheet->getCell('B' . $row)->getValue();

                    if (in_array($valueA, ['Tahun', 'Program Prioritas', 'Deskripsi', 'Status'])) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'E0F2FE',
                                ],
                            ],
                        ]);
                    }

                    if (str_starts_with((string) $valueA, 'Rencana Aksi')) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => [
                                    'rgb' => 'FFFFFF',
                                ],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => '15803D',
                                ],
                            ],
                        ]);
                    }

                    if ($valueB === 'No') {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => [
                                    'rgb' => 'FFFFFF',
                                ],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => '7C3AED',
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }

                    $sheet->getRowDimension($row)->setRowHeight(-1);
                }

                $sheet->getStyle("A1:K{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle("A5:K{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'CBD5E1',
                            ],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:K{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                $sheet->getStyle("A1:K{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_TOP);

                $sheet->getStyle("B6:B{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("D6:E{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("I6:J{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->freezePane('A5');
            },
        ];
    }
}