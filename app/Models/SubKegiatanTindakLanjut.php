<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanTindakLanjut extends Model
{
    protected $table = 'saplarin_sub_kegiatan_tindak_lanjut';
    protected $primaryKey = 'tindak_lanjut_id';

    protected $fillable = [
        'tindak_lanjut_laporan_id',
        'tindak_lanjut_uraian',
    ];
}