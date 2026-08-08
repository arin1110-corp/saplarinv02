<?php

namespace App\Exports;

use App\Models\ModelBBM;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class BBMExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths, WithEvents, WithCustomValueBinder
{
    protected $fields;
    protected $bulan;
    protected $tahun;

    public function __construct($fields, $bulan = null, $tahun = null)
    {
        $this->fields = $fields;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = ModelBBM::query()->where('bbm_status_pengajuan', 'Pengajuan Diterima')->where('bbm_status_laporan', 'Laporan Nota Diterima');

        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun);
        }

        $data = $query->orderBy('created_at', 'asc')->get();

        return $data->map(function ($item) {
            $row = [];

            foreach ($this->fields as $field) {
                switch ($field) {
                    case 'pengaju_nama':
                        $row[] = $item->bbm_pengaju_nama;
                        break;

                    case 'pengaju_nip':
                        $row[] = (string) $item->bbm_pengaju_nip;
                        break;

                    case 'bidang':
                        $row[] = $item->bbm_bidang_nama;
                        break;

                    case 'plat':
                        $row[] = $item->bbm_no_plat;
                        break;

                    case 'liter':
                        $row[] = $item->bbm_liter;
                        break;

                    case 'uraian':
                        $row[] = $item->bbm_uraian_kegiatan;
                        break;

                    case 'status_pengajuan':
                        $row[] = $item->bbm_status_pengajuan;
                        break;

                    case 'status_laporan':
                        $row[] = $item->bbm_status_laporan;
                        break;

                    case 'tanggal_pengajuan':
                        $row[] = $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-';
                        break;

                    case 'file_spt':
                        $row[] = $item->bbm_spt_file;
                        break;

                    case 'file_acc':
                        $row[] = $item->bbm_acc_pimpinan_file;
                        break;

                    case 'file_nota':
                        $row[] = $item->bbm_laporan_nota_file;
                        break;

                    case 'bukti_tambahan':
                        $bukti = $item->bbm_bukti_tambahan_file;

                        if (is_string($bukti)) {
                            $bukti = json_decode($bukti, true);
                        }

                        if (!is_array($bukti)) {
                            $bukti = [];
                        }

                        $links = collect($bukti)
                            ->map(function ($x, $i) {
                            return 'Bukti ' . ($i + 1) . ': ' . ($x['file'] ?? '-');
                            })
                            ->implode("\n");

                        $row[] = $links ?: '-';

                        break;

                    default:
                        $row[] = '';
                        break;
                }
            }

            return $row;
        });
    }

    public function headings(): array
    {
        $header = [];

        foreach ($this->fields as $field) {
            $header[] = match ($field) {
                'pengaju_nama' => 'Nama Pengaju',

                'pengaju_nip' => 'NIP',

                'bidang' => 'Bidang',

                'plat' => 'No Plat',

                'liter' => 'Liter',

                'uraian' => 'Uraian Kegiatan',

                'status_pengajuan' => 'Status Pengajuan',

                'status_laporan' => 'Status Laporan',

                'tanggal_pengajuan' => 'Tanggal Pengajuan',

                'file_spt' => 'File SPT',

                'file_acc' => 'File ACC Pimpinan',

                'file_nota' => 'File Nota',

                'bukti_tambahan' => 'Bukti Tambahan',

                default => $field,
            };
        }

        return $header;
    }

    /**
     * Styling dasar
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '2563EB',
                    ],
                ],

                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        $widths = [];

        foreach ($this->fields as $index => $field) {
            $column = $this->columnLetter($index + 1);

            $widths[$column] = match ($field) {
                'pengaju_nama' => 28,

                'pengaju_nip' => 22,

                'bidang' => 28,

                'plat' => 15,

                'liter' => 12,

                'uraian' => 40,

                'status_pengajuan' => 25,

                'status_laporan' => 25,

                'tanggal_pengajuan' => 20,

                'file_spt' => 45,

                'file_acc' => 45,

                'file_nota' => 45,

                'bukti_tambahan' => 50,

                default => 20,
            };
        }

        return $widths;
    }

    /**
     * Event setelah sheet selesai dibuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                /*
                |--------------------------------------------------------------------------
                | Semua cell
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,

                            'color' => [
                                'rgb' => 'D1D5DB',
                            ],
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Header
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,

                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => '2563EB',
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,

                        'vertical' => Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Tinggi Header
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(1)->setRowHeight(35);

                /*
                |--------------------------------------------------------------------------
                | Tinggi data
                |--------------------------------------------------------------------------
                */

                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(45);
                }

                /*
                |--------------------------------------------------------------------------
                | Freeze header
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A2');

                /*
                |--------------------------------------------------------------------------
                | Filter
                |--------------------------------------------------------------------------
                */

                $sheet->setAutoFilter('A1:' . $highestColumn . $highestRow);

                /*
                |--------------------------------------------------------------------------
                | Print setup
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $sheet->getPageSetup()->setFitToWidth(1);

                $sheet->getPageSetup()->setFitToHeight(0);

                /*
                |--------------------------------------------------------------------------
                | Margin
                |--------------------------------------------------------------------------
                */

                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setBottom(0.5);
                $sheet->getPageMargins()->setLeft(0.3);
                $sheet->getPageMargins()->setRight(0.3);
            },
        ];
    }

    /**
     * Convert nomor kolom menjadi huruf Excel
     */
    private function columnLetter($number)
    {
        $letter = '';

        while ($number > 0) {
            $mod = ($number - 1) % 26;

            $letter = chr(65 + $mod) . $letter;

            $number = intdiv($number - $mod, 26) - 1;
        }

        return $letter;
    }
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'B') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}