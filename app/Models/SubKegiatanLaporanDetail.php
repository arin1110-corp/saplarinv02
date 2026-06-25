<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanLaporanDetail extends Model
{
    protected $table = 'saplarin_sub_kegiatan_laporan_detail';
    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'detail_laporan_id',
        'detail_indikator_id',
        'detail_indikator_nama',
        'detail_target',
        'detail_realisasi',
        'detail_satuan',
    ];
}