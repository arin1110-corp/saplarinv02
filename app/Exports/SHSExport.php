<?php

namespace App\Exports;

use App\Models\ModelSHS;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SHSExport implements
    FromCollection,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    /**
     * Field lama.
     *
     * Tetap diterima untuk menjaga kompatibilitas
     * dengan controller/export lama.
     */
    protected array $field = [];

    /**
     * Filter status.
     */
    protected ?string $status = null;

    /**
     * Filter tahun.
     */
    protected ?string $tahun = null;

    /**
     * Nomor urut Excel.
     */
    protected int $rowNumber = 0;

    /**
     * Constructor.
     */
    public function __construct(
        $field = [],
        $status = null,
        $tahun = null
    ) {
        $this->field = is_array($field)
            ? $field
            : [];

        $this->status = $status !== null && $status !== ''
            ? $status
            : null;

        $this->tahun = $tahun !== null && $tahun !== ''
            ? (string) $tahun
            : null;
    }

    /**
     * ============================================================
     * AMBIL DATA
     * ============================================================
     *
     * Data SHS diambil beserta referensi harga.
     *
     * Maksimal yang digunakan dalam Excel:
     *
     * Survey I   = referensi ke-1
     * Survey II  = referensi ke-2
     * Survey III = referensi ke-3
     */
    public function collection()
    {
        $query = ModelSHS::with([
            'referensiHarga' => function ($query) {
                $query->orderBy('shs_referensi_id', 'asc');
            },
        ])
            ->orderBy('shs_tahun', 'desc')
            ->orderBy('shs_unit_nama')
            ->orderBy('shs_barang');

        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if ($this->tahun !== null) {
            $query->where('shs_tahun', $this->tahun);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($this->status !== null) {
            $query->where('shs_status', $this->status);
        }

        return $query->get();
    }

    /**
     * ============================================================
     * START CELL
     * ============================================================
     *
     * Data dimulai dari A4.
     *
     * Baris 1 = Header utama
     * Baris 2 = Sub header
     * Baris 3 = Nomor kolom
     * Baris 4 = Data
     */
    public function startCell(): string
    {
        return 'A4';
    }

    /**
     * ============================================================
     * MAPPING
     * ============================================================
     */
    public function map($row): array
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil referensi harga
        |--------------------------------------------------------------------------
        */

        $referensi = $row->referensiHarga instanceof Collection
            ? $row->referensiHarga->values()
            : collect($row->referensiHarga ?? [])->values();

        /*
        |--------------------------------------------------------------------------
        | Survey I
        |--------------------------------------------------------------------------
        */

        $survey1 = $referensi->get(0);

        $survey1Harga = $survey1
            ? ($survey1->shs_referensi_harga ?? '')
            : '';

        $survey1Link = $survey1
            ? ($survey1->shs_referensi_link ?? '')
            : '';

        /*
        |--------------------------------------------------------------------------
        | Survey II
        |--------------------------------------------------------------------------
        */

        $survey2 = $referensi->get(1);

        $survey2Harga = $survey2
            ? ($survey2->shs_referensi_harga ?? '')
            : '';

        $survey2Link = $survey2
            ? ($survey2->shs_referensi_link ?? '')
            : '';

        /*
        |--------------------------------------------------------------------------
        | Survey III
        |--------------------------------------------------------------------------
        */

        $survey3 = $referensi->get(2);

        $survey3Harga = $survey3
            ? ($survey3->shs_referensi_harga ?? '')
            : '';

        $survey3Link = $survey3
            ? ($survey3->shs_referensi_link ?? '')
            : '';

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |----------------------------------------------------------
            | 1. NO
            |----------------------------------------------------------
            */
            $this->getRowNumber(),

            /*
            |----------------------------------------------------------
            | 2. KODE KELOMPOK BARANG
            |----------------------------------------------------------
            */
            $row->shs_kode_kelompok ?? '',

            /*
            |----------------------------------------------------------
            | 3. URAIAN KELOMPOK BARANG
            |----------------------------------------------------------
            */
            $row->shs_kelompok_barang ?? '',

            /*
            |----------------------------------------------------------
            | 4. URAIAN / NAMA
            |----------------------------------------------------------
            */
            $row->shs_barang ?? '',

            /*
            |----------------------------------------------------------
            | 5. MERK
            |----------------------------------------------------------
            */
            $row->shs_merek ?? '',

            /*
            |----------------------------------------------------------
            | 6. SPESIFIKASI
            |----------------------------------------------------------
            */
            $row->shs_spesifikasi ?? '',

            /*
            |----------------------------------------------------------
            | 7. SATUAN
            |----------------------------------------------------------
            */
            $row->shs_satuan ?? '',

            /*
            |----------------------------------------------------------
            | 8. TKDN
            |----------------------------------------------------------
            */
            $row->shs_tkdn ?? '',

            /*
            |----------------------------------------------------------
            | 9. KELOMPOK
            |----------------------------------------------------------
            */
            $row->shs_kelompok ?? '',

            /*
            |----------------------------------------------------------
            | 10. SURVEY I - HARGA
            |----------------------------------------------------------
            */
            $survey1Harga,

            /*
            |----------------------------------------------------------
            | 11. SURVEY I - SUPPLIER / LINK
            |----------------------------------------------------------
            */
            $survey1Link,

            /*
            |----------------------------------------------------------
            | 12. SURVEY II - HARGA
            |----------------------------------------------------------
            */
            $survey2Harga,

            /*
            |----------------------------------------------------------
            | 13. SURVEY II - SUPPLIER / LINK
            |----------------------------------------------------------
            */
            $survey2Link,

            /*
            |----------------------------------------------------------
            | 14. SURVEY III - HARGA
            |----------------------------------------------------------
            */
            $survey3Harga,

            /*
            |----------------------------------------------------------
            | 15. SURVEY III - SUPPLIER / LINK
            |----------------------------------------------------------
            */
            $survey3Link,

            /*
            |----------------------------------------------------------
            | 16. REKOMENDASI HARGA
            |----------------------------------------------------------
            */
            $row->shs_harga ?? '',
        ];
    }

    /**
     * ============================================================
     * NOMOR URUT
     * ============================================================
     */
    protected function getRowNumber(): int
    {
        $this->rowNumber++;

        return $this->rowNumber;
    }

    /**
     * ============================================================
     * EVENTS
     * ============================================================
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | MERGE HEADER
                |--------------------------------------------------------------------------
                */

                /*
                | NO
                */
                $sheet->mergeCells('A1:A2');

                /*
                | KODE KELOMPOK
                */
                $sheet->mergeCells('B1:B2');

                /*
                | URAIAN KELOMPOK
                */
                $sheet->mergeCells('C1:C2');

                /*
                | BARANG / JASA
                */
                $sheet->mergeCells('D1:F1');

                /*
                | SATUAN
                */
                $sheet->mergeCells('G1:G2');

                /*
                | TKDN
                */
                $sheet->mergeCells('H1:H2');

                /*
                | KELOMPOK
                */
                $sheet->mergeCells('I1:I2');

                /*
                | SURVEY I
                */
                $sheet->mergeCells('J1:K1');

                /*
                | SURVEY II
                */
                $sheet->mergeCells('L1:M1');

                /*
                | SURVEY III
                */
                $sheet->mergeCells('N1:O1');

                /*
                | REKOMENDASI
                */
                $sheet->mergeCells('P1:P2');

                /*
                |--------------------------------------------------------------------------
                | HEADER BARIS 1
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A1', 'NO');

                $sheet->setCellValue(
                    'B1',
                    'KODE KELOMPOK BARANG'
                );

                $sheet->setCellValue(
                    'C1',
                    'URAIAN KELOMPOK BARANG'
                );

                $sheet->setCellValue(
                    'D1',
                    'BARANG/JASA YANG DIUSULKAN'
                );

                $sheet->setCellValue(
                    'G1',
                    'SATUAN'
                );

                $sheet->setCellValue(
                    'H1',
                    'TKDN (%)'
                );

                $sheet->setCellValue(
                    'I1',
                    'KELOMPOK'
                );

                $sheet->setCellValue(
                    'J1',
                    'SURVEY I (BARU)'
                );

                $sheet->setCellValue(
                    'L1',
                    'SURVEY II (BARU)'
                );

                $sheet->setCellValue(
                    'N1',
                    'SURVEY III (BARU)'
                );

                $sheet->setCellValue(
                    'P1',
                    'REKOMENDASI HARGA (RP)'
                );

                /*
                |--------------------------------------------------------------------------
                | HEADER BARIS 2
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue(
                    'D2',
                    'URAIAN/NAMA'
                );

                $sheet->setCellValue(
                    'E2',
                    'MERK'
                );

                $sheet->setCellValue(
                    'F2',
                    'SPESIFIKASI'
                );

                $sheet->setCellValue(
                    'J2',
                    'HARGA (RP)'
                );

                $sheet->setCellValue(
                    'K2',
                    'NAMA SUPPLIER/LINK SURVEY'
                );

                $sheet->setCellValue(
                    'L2',
                    'HARGA (RP)'
                );

                $sheet->setCellValue(
                    'M2',
                    'NAMA SUPPLIER/LINK SURVEY'
                );

                $sheet->setCellValue(
                    'N2',
                    'HARGA (RP)'
                );

                $sheet->setCellValue(
                    'O2',
                    'NAMA SUPPLIER/LINK SURVEY'
                );

                /*
                |--------------------------------------------------------------------------
                | NOMOR KOLOM
                |--------------------------------------------------------------------------
                |
                | Mengikuti urutan 16 kolom sebenarnya.
                |
                */

                $sheet->setCellValue('A3', '(1)');
                $sheet->setCellValue('B3', '(2)');
                $sheet->setCellValue('C3', '(3)');
                $sheet->setCellValue('D3', '(4)');
                $sheet->setCellValue('E3', '');
                $sheet->setCellValue('F3', '(5)');
                $sheet->setCellValue('G3', '(6)');
                $sheet->setCellValue('H3', '(7)');
                $sheet->setCellValue('I3', '(8)');
                $sheet->setCellValue('J3', '(9)');
                $sheet->setCellValue('K3', '(10)');
                $sheet->setCellValue('L3', '(11)');
                $sheet->setCellValue('M3', '(12)');
                $sheet->setCellValue('N3', '(13)');
                $sheet->setCellValue('O3', '(14)');
                $sheet->setCellValue('P3', '(15)');

                /*
                |--------------------------------------------------------------------------
                | STYLE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('A1:P3')
                    ->applyFromArray([

                        'font' => [
                            'bold' => true,
                            'size' => 10,
                        ],

                    'alignment' => [
                        'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                        Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                            Border::BORDER_THIN,
                        ],
                    ],

                    ]);

                /*
                |--------------------------------------------------------------------------
                | TINGGI BARIS HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(32);

                $sheet
                    ->getRowDimension(2)
                    ->setRowHeight(50);

                $sheet
                    ->getRowDimension(3)
                    ->setRowHeight(20);

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                $highestRow = $sheet->getHighestRow();

                if ($highestRow >= 4) {

                    /*
                    |--------------------------------------------------------------------------
                    | BORDER + WRAP
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("A4:P{$highestRow}")
                        ->applyFromArray([

                        'alignment' => [
                            'vertical' =>
                            Alignment::VERTICAL_TOP,

                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                Border::BORDER_THIN,
                            ],
                        ],

                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | NO
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("A4:A{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | TKDN
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("H4:H{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | SATUAN
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("G4:G{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | HARGA SURVEY I
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("J4:J{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    /*
                    |--------------------------------------------------------------------------
                    | HARGA SURVEY II
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("L4:L{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    /*
                    |--------------------------------------------------------------------------
                    | HARGA SURVEY III
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("N4:N{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    /*
                    |--------------------------------------------------------------------------
                    | REKOMENDASI
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("P4:P{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    /*
                    |--------------------------------------------------------------------------
                    | ALIGNMENT HARGA
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("J4:J{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    $sheet
                        ->getStyle("L4:L{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    $sheet
                        ->getStyle("N4:N{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    $sheet
                        ->getStyle("P4:P{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | LINK SURVEY
                    |--------------------------------------------------------------------------
                    |
                    | K = Survey I
                    | M = Survey II
                    | O = Survey III
                    |
                    */

                    for ($row = 4; $row <= $highestRow; $row++) {

                        $linkColumns = [
                            'K',
                            'M',
                            'O',
                        ];

                        foreach ($linkColumns as $column) {

                            $cell = $sheet->getCell(
                                "{$column}{$row}"
                            );

                            $value = trim(
                                (string) $cell->getValue()
                            );

                            if ($value === '') {
                                continue;
                            }

                            /*
                            | Pastikan menjadi URL.
                            */
                            if (
                                !str_starts_with(
                                    strtolower($value),
                                    'http://'
                                )
                                &&
                                !str_starts_with(
                                    strtolower($value),
                                    'https://'
                                )
                            ) {
                                continue;
                            }

                            /*
                            | Set hyperlink.
                            */
                            $cell->getHyperlink()
                                ->setUrl($value);

                            /*
                            | Gunakan URL sebagai text.
                            */
                            $cell->setValueExplicit(
                                $value,
                                DataType::TYPE_STRING
                            );
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A4');

                /*
                |--------------------------------------------------------------------------
                | WIDTH KOLOM
                |--------------------------------------------------------------------------
                */

                $widths = [

                    'A' => 6,

                    'B' => 20,

                    'C' => 30,

                    'D' => 30,

                    'E' => 18,

                    'F' => 40,

                    'G' => 12,

                    'H' => 12,

                    'I' => 18,

                    'J' => 18,

                    'K' => 40,

                    'L' => 18,

                    'M' => 40,

                    'N' => 18,

                    'O' => 40,

                    'P' => 22,

                ];

                foreach ($widths as $column => $width) {

                    $sheet
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                if ($highestRow >= 3) {

                    $sheet->setAutoFilter(
                        "A3:P{$highestRow}"
                    );
                }
            },
        ];
    }
}