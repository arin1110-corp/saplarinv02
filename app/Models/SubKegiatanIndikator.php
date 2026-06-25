<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanIndikator extends Model
{
    protected $table = 'saplarin_sub_kegiatan_indikator';
    protected $primaryKey = 'indikator_id';

    protected $fillable = [
        'indikator_uid',
        'indikator_sub_kegiatan_id',
        'indikator_nama',
        'indikator_target',
        'indikator_satuan',
        'indikator_status',
    ];

    public function subKegiatan()
    {
        return $this->belongsTo(ModelSubKegiatan::class, 'indikator_sub_kegiatan_id', 'sub_kegiatan_id');
    }
}