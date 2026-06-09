<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelPrioritas extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_prioritas';
    protected $primaryKey = 'prioritas_id';

    protected $fillable = [
        'prioritas_uid',
        'prioritas_tahun',
        'prioritas_judul',
        'prioritas_deskripsi',
        'prioritas_status',
        'prioritas_created_by',
        'prioritas_created_by_nama',
    ];

    public function bukti()
    {
        return $this->hasMany(
            ModelPrioritasBukti::class,
            'bukti_prioritas_id',
            'prioritas_id'
        );
    }

    public function buktiAktif()
    {
        return $this->hasMany(
            ModelPrioritasBukti::class,
            'bukti_prioritas_id',
            'prioritas_id'
        )->where('bukti_status', 'Aktif');
    }
}