<?php

namespace App\Exports;

use App\Models\ModelBBM;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BBMExport implements FromCollection, WithHeadings
{
    protected $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function collection()
    {
        return ModelBBM::get()->map(function ($item) {
            $row = [];

            foreach ($this->fields as $field) {
                switch ($field) {
                    case 'pengaju_nama':
                        $row[] = $item->bbm_pengaju_nama;
                        break;

                    case 'pengaju_nip':
                        $row[] = '="' . $item->bbm_pengaju_nip . '"';
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
                        $row[] = $item->created_at;
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
                                return 'Bukti ' . ($i + 1) . ': ' . $x['file'];
                            })
                            ->implode("\n");

                        $row[] = $links;

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
                'uraian' => 'Uraian',
                'status_pengajuan' => 'Status Pengajuan',
                'status_laporan' => 'Status Laporan',
                'tanggal_pengajuan' => 'Tanggal Pengajuan',
                'file_spt' => 'File SPT',
                'file_acc' => 'File ACC Pimpinan',
                'file_nota' => 'File Nota',
                'bukti_tambahan' => 'Bukti Tambahan',
            };
        }

        return $header;
    }
}