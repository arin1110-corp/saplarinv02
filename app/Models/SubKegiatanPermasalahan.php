<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanPermasalahan extends Model
{
    protected $table = 'saplarin_sub_kegiatan_permasalahan';
    protected $primaryKey = 'permasalahan_id';

    protected $fillable = [
        'permasalahan_laporan_id',
        'permasalahan_uraian',
    ];
}