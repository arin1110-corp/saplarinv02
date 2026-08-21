<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StandarHargaPermintaanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected $tahun;
    protected $jenis;

    public function __construct($tahun, $jenis)
    {
        $this->tahun = $tahun;
        $this->jenis = strtoupper($jenis);
    }

    /**
     * --------------------------------------------------------------------------
     * DATA
     * --------------------------------------------------------------------------
     */
    public function collection()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL MASTER STANDAR HARGA
        |--------------------------------------------------------------------------
        */

        $standarHarga = DB::table('saplarin_standar_harga')
            ->where(
                'standar_harga_tahun',
                $this->tahun
            )
            ->where(
                'standar_harga_jenis',
                $this->jenis
            )
            ->where(
                'standar_harga_status',
                true
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PENGGUNAAN
        |--------------------------------------------------------------------------
        |
        | Hanya penggunaan yang:
        |
        | - sesuai tahun
        | - status aktif
        |
        */

        $penggunaan = DB::table(
            'saplarin_standar_harga_penggunaan'
        )
            ->where(
                'penggunaan_tahun',
                $this->tahun
            )
            ->where(
                'penggunaan_status',
                true
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | KELOMPOKKAN BERDASARKAN MASTER STANDAR HARGA
        |--------------------------------------------------------------------------
        */

        $penggunaanPerStandar = $penggunaan
            ->groupBy(
                'penggunaan_standar_harga'
            );


        /*
        |--------------------------------------------------------------------------
        | HANYA TAMPILKAN YANG DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        return $standarHarga

            ->filter(function ($item) use (
                $penggunaanPerStandar
            ) {

                return $penggunaanPerStandar->has(
                    $item->standar_harga_id
                );

            })

            ->map(function ($item) use (
                $penggunaanPerStandar
            ) {

                /*
                |--------------------------------------------------------------------------
                | AMBIL SEMUA PENGGUNAAN
                |--------------------------------------------------------------------------
                */

                $dataPenggunaan =
                    $penggunaanPerStandar->get(
                        $item->standar_harga_id
                    );


                /*
                |--------------------------------------------------------------------------
                | GABUNGKAN UNIT
                |--------------------------------------------------------------------------
                |
                | Contoh:
                |
                | Unit A
                | Unit B
                | Unit C
                |
                | menjadi:
                |
                | Unit A, Unit B, Unit C
                |
                */

                $unit = $dataPenggunaan

                    ->map(function ($penggunaan) {

                        return trim(
                            $penggunaan->penggunaan_unit ?? ''
                        );

                    })

                    ->filter(function ($unit) {

                        return $unit !== '';

                    })

                    ->unique()

                    ->sort()

                    ->values()

                    ->implode(', ');


                /*
                |--------------------------------------------------------------------------
                | SIMPAN UNIT UNTUK EXPORT
                |--------------------------------------------------------------------------
                */

                $item->export_unit =
                    $unit ?: '-';


                return $item;

            })

            ->values();
    }


    /**
     * --------------------------------------------------------------------------
     * HEADER EXCEL
     * --------------------------------------------------------------------------
     */
    public function headings(): array
    {
        return [

            'NO',

            'JENIS',

            'TAHUN',

            'KODE KELOMPOK BARANG',

            'URAIAN KELOMPOK BARANG',

            'ID STANDAR HARGA',

            'KODE BARANG',

            'URAIAN BARANG',

            'SPESIFIKASI',

            'SATUAN',

            'HARGA SATUAN',

            'KODE REKENING',

            'DIGUNAKAN OLEH UNIT',

        ];
    }


    /**
     * --------------------------------------------------------------------------
     * MAPPING
     * --------------------------------------------------------------------------
     */
    public function map($item): array
    {
        static $no = 0;

        $no++;


        return [

            $no,

            $item->standar_harga_jenis,

            $item->standar_harga_tahun,

            $item->standar_harga_kode_kelompok,

            $item->standar_harga_uraian_kelompok,

            $item->standar_harga_id_standar,

            $item->standar_harga_kode_barang,

            $item->standar_harga_uraian_barang,

            $item->standar_harga_spesifikasi,

            $item->standar_harga_satuan,

            $item->standar_harga_satuan_harga,

            $item->standar_harga_kode_rekening,

            $item->export_unit,

        ];
    }


    /**
     * --------------------------------------------------------------------------
     * STYLE
     * --------------------------------------------------------------------------
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle('A1:M1')
            ->applyFromArray([

                'font' => [

                    'bold' => true,

                ],

                'alignment' => [

                    'horizontal' => 'center',

                    'vertical' => 'center',

                    'wrapText' => true,

                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | TINGGI HEADER
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getRowDimension(1)
            ->setRowHeight(35);


        /*
        |--------------------------------------------------------------------------
        | VERTICAL ALIGNMENT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle(
                "A1:M{$highestRow}"
            )
            ->getAlignment()
            ->setVertical('top');


        /*
        |--------------------------------------------------------------------------
        | WRAP TEXT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle(
                "E1:E{$highestRow}"
            )
            ->getAlignment()
            ->setWrapText(true);

        $sheet
            ->getStyle(
                "H1:H{$highestRow}"
            )
            ->getAlignment()
            ->setWrapText(true);

        $sheet
            ->getStyle(
                "I1:I{$highestRow}"
            )
            ->getAlignment()
            ->setWrapText(true);

        $sheet
            ->getStyle(
                "M1:M{$highestRow}"
            )
            ->getAlignment()
            ->setWrapText(true);


        /*
        |--------------------------------------------------------------------------
        | FORMAT HARGA
        |--------------------------------------------------------------------------
        */

        if ($highestRow >= 2) {

            $sheet
                ->getStyle(
                    "K2:K{$highestRow}"
                )
                ->getNumberFormat()
                ->setFormatCode('#,##0');

        }


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A2');


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter(
            "A1:M{$highestRow}"
        );


        return [];
    }
}