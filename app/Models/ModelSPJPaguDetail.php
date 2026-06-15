<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSPJPaguDetail extends Model
{
    protected $table = 'saplarin_spj_pagu_detail';
    protected $primaryKey = 'spj_pagu_detail_id';

    protected $fillable = [
        'spj_pagu_detail_pagu_id',
        'spj_pagu_detail_jenis',
        'spj_pagu_detail_urutan',
        'spj_pagu_detail_nominal',
        'spj_pagu_detail_tanggal',
        'spj_pagu_detail_keterangan',
    ];

    protected $casts = [
        'spj_pagu_detail_tanggal' => 'date',
    ];
}