<?php

namespace App\Exports;

use App\Models\ModelSHS;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SHSExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $field;
    protected $status;

    public function __construct($field, $status)
    {
        $this->field = $field ?? [];
        $this->status = $status;
    }

    /**
     * Ambil data SHS
     */
    public function collection()
    {
        $query = ModelSHS::query();

        if ($this->status) {
            $query->where('shs_status', $this->status);
        }

        return $query->get();
    }

    /**
     * Header Excel
     */
    public function headings(): array
    {
        return ['NO', 'KODE KELOMPOK BARANG', 'URAIAN KELOMPOK BARANG', 'URAIAN/NAMA', 'MERK', 'SPESIFIKASI', 'SATUAN', 'TKDN (%)', 'KELOMPOK', 'SURVEY I - HARGA (RP)', 'SURVEY I - NAMA SUPPLIER/LINK SURVEY', 'SURVEY II - HARGA (RP)', 'SURVEY II - NAMA SUPPLIER/LINK SURVEY', 'SURVEY III - HARGA (RP)', 'SURVEY III - NAMA SUPPLIER/LINK SURVEY', 'REKOMENDASI HARGA (RP)'];
    }

    /**
     * Mapping data ke Excel
     */
    public function map($row): array
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data survey
        |--------------------------------------------------------------------------
        */

        $survey = $this->parseSurvey($row->shs_link_survei ?? null);

        $survey1 = $survey[0] ?? [];
        $survey2 = $survey[1] ?? [];
        $survey3 = $survey[2] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Harga survey
        |--------------------------------------------------------------------------
        */

        $hargaSurvey1 = $this->getSurveyValue($survey1, ['harga', 'price', 'nilai', 'nominal']);

        $hargaSurvey2 = $this->getSurveyValue($survey2, ['harga', 'price', 'nilai', 'nominal']);

        $hargaSurvey3 = $this->getSurveyValue($survey3, ['harga', 'price', 'nilai', 'nominal']);

        /*
        |--------------------------------------------------------------------------
        | Supplier / Link Survey
        |--------------------------------------------------------------------------
        */

        $supplierSurvey1 = $this->getSupplierOrLink($survey1);
        $supplierSurvey2 = $this->getSupplierOrLink($survey2);
        $supplierSurvey3 = $this->getSupplierOrLink($survey3);

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [
            $this->rowNumber(),

            // KODE KELOMPOK BARANG
            $row->shs_kode_kelompok ?? '',

            // URAIAN KELOMPOK BARANG
            $row->shs_kelompok_barang ?? '',

            // URAIAN/NAMA
            $row->shs_barang ?? '',

            // MERK
            $row->shs_merek ?? '',

            // SPESIFIKASI
            $this->buildSpesifikasi($row),

            // SATUAN
            $row->shs_satuan ?? '',

            // TKDN
            $row->shs_tkdn ?? '',

            // KELOMPOK
            $row->shs_kelompok ?? '',

            // SURVEY I
            $hargaSurvey1,

            $supplierSurvey1,

            // SURVEY II
            $hargaSurvey2,

            $supplierSurvey2,

            // SURVEY III
            $hargaSurvey3,

            $supplierSurvey3,

            // REKOMENDASI HARGA
            $row->shs_harga ?? '',
        ];
    }

    /**
     * Nomor urut
     */
    protected $rowNumber = 0;

    protected function rowNumber()
    {
        $this->rowNumber++;

        return $this->rowNumber;
    }

    /**
     * Gabungkan spesifikasi.
     *
     * Saat ini menggunakan:
     * shs_tipe + shs_spesifikasi
     */
    protected function buildSpesifikasi($row)
    {
        $data = [];

        if (!empty($row->shs_tipe)) {
            $data[] = $row->shs_tipe;
        }

        if (!empty($row->shs_spesifikasi)) {
            $data[] = $row->shs_spesifikasi;
        }

        return implode(' - ', $data);
    }

    /**
     * Parse JSON survey.
     *
     * Mendukung beberapa bentuk JSON.
     */
    protected function parseSurvey($value): array
    {
        if (empty($value)) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau sudah berupa array
        |--------------------------------------------------------------------------
        */

        if (is_array($value)) {
            return $this->normalizeSurvey($value);
        }

        /*
        |--------------------------------------------------------------------------
        | Decode JSON
        |--------------------------------------------------------------------------
        */

        $decoded = json_decode($value, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $this->normalizeSurvey($decoded);
    }

    /**
     * Normalisasi struktur survey.
     */
    protected function normalizeSurvey(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Format:
        |
        | [
        |   survey1,
        |   survey2,
        |   survey3
        | ]
        |--------------------------------------------------------------------------
        */

        if (array_is_list($data)) {
            return array_values($data);
        }

        /*
        |--------------------------------------------------------------------------
        | Format:
        |
        | {
        |   "survey_1": {...},
        |   "survey_2": {...},
        |   "survey_3": {...}
        | }
        |--------------------------------------------------------------------------
        */

        $result = [];

        foreach (['survey_1', 'survey1', 'survey_i', 'surveyI', '1'] as $key) {
            if (isset($data[$key])) {
                $result[0] = $data[$key];
                break;
            }
        }

        foreach (['survey_2', 'survey2', 'survey_ii', 'surveyII', '2'] as $key) {
            if (isset($data[$key])) {
                $result[1] = $data[$key];
                break;
            }
        }

        foreach (['survey_3', 'survey3', 'survey_iii', 'surveyIII', '3'] as $key) {
            if (isset($data[$key])) {
                $result[2] = $data[$key];
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau bukan format di atas, anggap sebagai 1 survey
        |--------------------------------------------------------------------------
        */

        if (empty($result)) {
            $result[] = $data;
        }

        ksort($result);

        return array_values($result);
    }

    /**
     * Ambil harga survey.
     */
    protected function getSurveyValue($survey, array $keys)
    {
        if (!is_array($survey)) {
            return '';
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $survey) && $survey[$key] !== null && $survey[$key] !== '') {
                return $survey[$key];
            }
        }

        return '';
    }

    /**
     * Ambil nama supplier atau link survey.
     */
    protected function getSupplierOrLink($survey)
    {
        if (!is_array($survey)) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Prioritas nama supplier
        |--------------------------------------------------------------------------
        */

        foreach (['supplier', 'supplier_nama', 'nama_supplier', 'nama', 'vendor', 'vendor_nama'] as $key) {
            if (array_key_exists($key, $survey) && $survey[$key] !== null && $survey[$key] !== '') {
                return $survey[$key];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau tidak ada supplier, gunakan link
        |--------------------------------------------------------------------------
        */

        foreach (['link', 'url', 'link_survei', 'survey_link'] as $key) {
            if (array_key_exists($key, $survey) && $survey[$key] !== null && $survey[$key] !== '') {
                return $survey[$key];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau survey berupa string
        |--------------------------------------------------------------------------
        */

        if (isset($survey['value']) && is_string($survey['value'])) {
            return $survey['value'];
        }

        return '';
    }

    /**
     * Styling dan merge header.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Header utama
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');

                /*
                |--------------------------------------------------------------------------
                | Survey I
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('J1:K1');

                /*
                |--------------------------------------------------------------------------
                | Survey II
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('L1:M1');

                /*
                |--------------------------------------------------------------------------
                | Survey III
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('N1:O1');

                /*
                |--------------------------------------------------------------------------
                | Rekomendasi
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('P1:P2');

                /*
                |--------------------------------------------------------------------------
                | Header survey
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('J1', 'SURVEY I (BARU)');
                $sheet->setCellValue('L1', 'SURVEY II (BARU)');
                $sheet->setCellValue('N1', 'SURVEY III (BARU)');

                $sheet->setCellValue('J2', 'HARGA (RP)');
                $sheet->setCellValue('K2', 'NAMA SUPPLIER/LINK SURVEY');

                $sheet->setCellValue('L2', 'HARGA (RP)');
                $sheet->setCellValue('M2', 'NAMA SUPPLIER/LINK SURVEY');

                $sheet->setCellValue('N2', 'HARGA (RP)');
                $sheet->setCellValue('O2', 'NAMA SUPPLIER/LINK SURVEY');

                /*
                |--------------------------------------------------------------------------
                | Header lainnya
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'KODE KELOMPOK BARANG');
                $sheet->setCellValue('C1', 'URAIAN KELOMPOK BARANG');
                $sheet->setCellValue('D1', 'URAIAN/NAMA');
                $sheet->setCellValue('E1', 'MERK');
                $sheet->setCellValue('F1', 'SPESIFIKASI');
                $sheet->setCellValue('G1', 'SATUAN');
                $sheet->setCellValue('H1', 'TKDN (%)');
                $sheet->setCellValue('I1', 'KELOMPOK');
                $sheet->setCellValue('P1', 'REKOMENDASI HARGA (RP)');

                /*
                |--------------------------------------------------------------------------
                | Nomor kolom sesuai template
                |--------------------------------------------------------------------------
                |
                | A = 1
                | B = 2
                | C = 3
                | D = 4
                | E = -
                | F = 5
                | G = 6
                | H = 7
                | I = 8
                | J = 9
                | K = 10
                | L = 11
                | M = 10
                | N = 11
                | O = 12
                | P = 13
                |--------------------------------------------------------------------------
                */

                /*
                |--------------------------------------------------------------------------
                | Baris nomor template
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('A3', '(1)');
                $sheet->setCellValue('B3', '(2)');
                $sheet->setCellValue('C3', '(3)');
                $sheet->setCellValue('D3', '(4)');
                $sheet->setCellValue('F3', '(5)');
                $sheet->setCellValue('G3', '(6)');
                $sheet->setCellValue('H3', '(7)');
                $sheet->setCellValue('I3', '(8)');
                $sheet->setCellValue('J3', '(9)');
                $sheet->setCellValue('K3', '(10)');
                $sheet->setCellValue('L3', '(11)');
                $sheet->setCellValue('M3', '(10)');
                $sheet->setCellValue('N3', '(11)');
                $sheet->setCellValue('O3', '(12)');
                $sheet->setCellValue('P3', '(13)');

                /*
                |--------------------------------------------------------------------------
                | Styling header
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:P3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Background header
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:P2')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Tinggi header
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(1)->setRowHeight(32);
                $sheet->getRowDimension(2)->setRowHeight(48);
                $sheet->getRowDimension(3)->setRowHeight(20);

                /*
                |--------------------------------------------------------------------------
                | Alignment data
                |--------------------------------------------------------------------------
                */

                $highestRow = $sheet->getHighestRow();

                if ($highestRow >= 4) {
                    $sheet->getStyle("A4:P{$highestRow}")->applyFromArray([
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Kolom angka
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle("A4:A{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet
                        ->getStyle("H4:H{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet
                        ->getStyle("J4:J{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet
                        ->getStyle("L4:L{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet
                        ->getStyle("N4:N{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet
                        ->getStyle("P4:P{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                /*
                |--------------------------------------------------------------------------
                | Freeze header
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A4');

                /*
                |--------------------------------------------------------------------------
                | Lebar kolom
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
                    'K' => 35,
                    'L' => 18,
                    'M' => 35,
                    'N' => 18,
                    'O' => 35,
                    'P' => 20,
                ];

                foreach ($widths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }
            },
        ];
    }
}
