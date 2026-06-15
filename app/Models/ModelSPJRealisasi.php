<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSPJRealisasi extends Model
{
    protected $table = 'saplarin_spj_realisasi';
    protected $primaryKey = 'spj_id';

    protected $fillable = [
        'spj_uid',
        'spj_pagu_id',
        'spj_uraian',
        'spj_nominal',
        'spj_tanggal',
        'spj_tanggal_input',
        'spj_file',
        'spj_operator_id',
        'spj_operator_nama',
        'spj_operator_nip',
        'spj_bidang_id',
        'spj_bidang_nama',
        'spj_status',
    ];

    protected $casts = [
        'spj_tanggal' => 'date',
        'spj_tanggal_input' => 'datetime',
    ];

    public function pagu()
    {
        return $this->belongsTo(ModelSPJPagu::class, 'spj_pagu_id', 'spj_pagu_id');
    }
}