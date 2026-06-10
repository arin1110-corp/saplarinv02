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
            $totalTarget = $prioritas->rencana->sum(function ($r) {
                return (int) $r->rencana_target;
            });

            $totalCapaian = $prioritas->rencana->sum(function ($r) {
                return $r->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');
            });

            $persenPrioritas = $totalTarget > 0 ? ($totalCapaian / $totalTarget) * 100 : 0;
            $persenPrioritas = min($persenPrioritas, 100);

            $rows[] = [
                'PROGRAM PRIORITAS',
                'Tahun',
                $prioritas->prioritas_tahun,
                'Nama',
                $prioritas->prioritas_judul,
                'Status',
                $prioritas->prioritas_status,
            ];

            $rows[] = [
                '',
                'Deskripsi',
                $prioritas->prioritas_deskripsi ?: '-',
                '',
                '',
                '',
                '',
            ];

            $rows[] = [
                '',
                'Total Target',
                $totalTarget,
                'Total Capaian',
                $totalCapaian,
                'Persentase',
                number_format($persenPrioritas, 2, ',', '.') . '%',
            ];

            $rows[] = [];

            $rows[] = [
                'No',
                'Rencana Aksi',
                'Target',
                'Capaian Aktif',
                'Persentase Rencana',
                'Operator Rencana',
                'Bidang Rencana',
            ];

            $noRencana = 1;

            foreach ($prioritas->rencana as $rencana) {
                $targetRencana = (int) $rencana->rencana_target;

                $capaianAktifRencana = $rencana->capaian
                    ->where('capaian_status', 'Aktif')
                    ->sum('capaian_jumlah');

                $persenRencana = $targetRencana > 0 ? ($capaianAktifRencana / $targetRencana) * 100 : 0;
                $persenRencana = min($persenRencana, 100);

                $rows[] = [
                    $noRencana++,
                    $rencana->rencana_judul,
                    $targetRencana,
                    $capaianAktifRencana,
                    number_format($persenRencana, 2, ',', '.') . '%',
                    $rencana->rencana_user_nama ?? '-',
                    $rencana->rencana_bidang_nama ?? '-',
                ];

                $rows[] = [
                    '',
                    'Detail Capaian',
                    'Jumlah',
                    'Persentase',
                    'Tanggal',
                    'Operator',
                    'Bidang',
                ];

                if ($rencana->capaian->count() > 0) {
                    foreach ($rencana->capaian as $capaian) {
                        $jumlah = (int) ($capaian->capaian_jumlah ?? 1);

                        $persenCapaian = $targetRencana > 0 ? ($jumlah / $targetRencana) * 100 : 0;
                        $persenCapaian = min($persenCapaian, 100);

                        $tanggalMulai = $capaian->capaian_tanggal_mulai
                            ? $capaian->capaian_tanggal_mulai->format('d/m/Y')
                            : '-';

                        $tanggalSelesai = $capaian->capaian_tanggal_selesai
                            ? $capaian->capaian_tanggal_selesai->format('d/m/Y')
                            : '-';

                        $rows[] = [
                            '',
                            $capaian->capaian_judul,
                            $jumlah,
                            number_format($persenCapaian, 2, ',', '.') . '%',
                            $tanggalMulai . ' - ' . $tanggalSelesai,
                            $capaian->capaian_user_nama ?? '-',
                            $capaian->capaian_bidang_nama ?? '-',
                        ];

                        $rows[] = [
                            '',
                            'Deskripsi',
                            $capaian->capaian_deskripsi,
                            'Status',
                            $capaian->capaian_status,
                            'File Bukti',
                            $capaian->files->count(),
                        ];
                    }
                } else {
                    $rows[] = [
                        '',
                        'Belum ada capaian',
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

                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('A3:G3');

                $sheet->getStyle('A1:G3')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(16);
                $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                for ($row = 1; $row <= $highestRow; $row++) {
                    $valueA = $sheet->getCell('A' . $row)->getValue();
                    $valueB = $sheet->getCell('B' . $row)->getValue();

                    if ($valueA === 'PROGRAM PRIORITAS') {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '1D4ED8'],
                            ],
                        ]);
                    }

                    if ($valueA === 'No') {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '15803D'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }

                    if ($valueB === 'Detail Capaian') {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '7C3AED'],
                            ],
                        ]);
                    }

                    if ($valueB === 'Deskripsi') {
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC'],
                            ],
                        ]);
                    }
                }

                $sheet->getStyle("A1:G{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle("A5:G{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:G{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_TOP);

                $sheet->freezePane('A5');
            },
        ];
    }
}