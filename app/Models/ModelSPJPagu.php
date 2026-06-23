<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSPJPagu extends Model
{
    protected $table = 'saplarin_spj_pagu';
    protected $primaryKey = 'spj_pagu_id';

    protected $fillable = [
        'spj_pagu_uid',
        'spj_pagu_program_id',
        'spj_pagu_kegiatan_id',
        'spj_pagu_sub_kegiatan_id',
        'spj_pagu_tahun',
        'spj_pagu_final',
        'spj_pagu_status',
        'spj_pagu_created_by',
        'spj_pagu_created_by_nama',
        'spj_pagu_unit_id',
    ];

    public function detail()
    {
        return $this->hasMany(ModelSPJPaguDetail::class, 'spj_pagu_detail_pagu_id', 'spj_pagu_id');
    }

    public function realisasi()
    {
        return $this->hasMany(ModelSPJRealisasi::class, 'spj_pagu_id', 'spj_pagu_id');
    }

    public function program()
    {
        return $this->belongsTo(ModelProgram::class, 'spj_pagu_program_id', 'program_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(ModelKegiatan::class, 'spj_pagu_kegiatan_id', 'kegiatan_id');
    }

    public function subKegiatan()
    {
        return $this->belongsTo(ModelSubKegiatan::class, 'spj_pagu_sub_kegiatan_id', 'sub_kegiatan_id');
    }
    public function unit()
    {
        return $this->belongsTo(ModelSPJUnit::class, 'spj_pagu_unit_id', 'unit_id');
    }
}