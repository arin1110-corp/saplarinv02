<?php

namespace App\Imports;

use App\Models\ModelStandarHarga;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StandarHargaImport implements ToCollection, WithHeadingRow
{
    protected $tahun;

    protected $jenis;

    public function __construct($tahun, $jenis)
    {
        $this->tahun = $tahun;
        $this->jenis = $jenis;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | SKIP BARIS KOSONG
            |--------------------------------------------------------------------------
            */

            if (
                empty($row['uraian_barang']) &&
                empty($row['kode_barang']) &&
                empty($row['id_standar_harga'])
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | NORMALISASI HARGA
            |--------------------------------------------------------------------------
            */

            $harga = $row['harga_satuan'] ?? 0;

            if (is_string($harga)) {

                /*
                | Hilangkan Rp dan spasi
                */

                $harga = str_replace(
                    ['Rp', 'rp', ' '],
                    '',
                    $harga
                );

                /*
                | Format Indonesia:
                |
                | 1.500.000
                | 25.000
                | 2.500.000,50
                |
                */

                if (str_contains($harga, ',')) {

                    $harga = str_replace('.', '', $harga);

                    $harga = str_replace(',', '.', $harga);

                } else {

                    $harga = str_replace('.', '', $harga);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | UID
            |--------------------------------------------------------------------------
            */

            $uid = (string) Str::uuid();


            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA
            |--------------------------------------------------------------------------
            */

            ModelStandarHarga::create([

                /*
                |--------------------------------------------------------------------------
                | IDENTITAS
                |--------------------------------------------------------------------------
                */

                'standar_harga_uid'
                    => $uid,

                'standar_harga_tahun'
                    => $this->tahun,

                'standar_harga_jenis'
                    => $this->jenis,


                /*
                |--------------------------------------------------------------------------
                | KELOMPOK BARANG
                |--------------------------------------------------------------------------
                */

                'standar_harga_kode_kelompok'
                    => $row['kode_kelompok_barang'] ?? null,

                'standar_harga_uraian_kelompok'
                    => $row['uraian_kelompok_barang'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | STANDAR HARGA
                |--------------------------------------------------------------------------
                */

                'standar_harga_id_standar'
                    => $row['id_standar_harga'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | BARANG
                |--------------------------------------------------------------------------
                */

                'standar_harga_kode_barang'
                    => $row['kode_barang'] ?? null,

                'standar_harga_uraian_barang'
                    => $row['uraian_barang'] ?? null,

                'standar_harga_spesifikasi'
                    => $row['spesifikasi'] ?? null,

                'standar_harga_satuan'
                    => $row['satuan'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | HARGA
                |--------------------------------------------------------------------------
                */

                'standar_harga_satuan_harga'
                    => is_numeric($harga)
                        ? $harga
                        : 0,


                /*
                |--------------------------------------------------------------------------
                | KODE REKENING
                |--------------------------------------------------------------------------
                */

                'standar_harga_kode_rekening'
                    => $row['kode_rekening'] ?? null,


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'standar_harga_status'
                    => true,


                /*
                |--------------------------------------------------------------------------
                | AUDIT
                |--------------------------------------------------------------------------
                */

                'standar_harga_input_nip'
                    => session('pegawai_nip'),

                'standar_harga_input_nama'
                    => session('pegawai_nama'),

            ]);
        }
    }
}