<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanSolusi extends Model
{
    protected $table = 'saplarin_sub_kegiatan_solusi';
    protected $primaryKey = 'solusi_id';

    protected $fillable = [
        'solusi_laporan_id',
        'solusi_uraian',
    ];
}