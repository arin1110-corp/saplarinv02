<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelPrioritasBukti extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_prioritas_bukti';
    protected $primaryKey = 'bukti_id';

    protected $fillable = [
        'bukti_uid',
        'bukti_prioritas_id',
        'bukti_op_id',
        'bukti_op_bidang',
        'bukti_deskripsi_kegiatan',
        'bukti_tanggal_kegiatan',
        'bukti_user_id',
        'bukti_user_nama',
        'bukti_user_nip',
        'bukti_bidang_id',
        'bukti_bidang_nama',
        'bukti_status',
    ];

    protected $casts = [
        'bukti_tanggal_kegiatan' => 'date',
    ];

    public function prioritas()
    {
        return $this->belongsTo(
            ModelPrioritas::class,
            'bukti_prioritas_id',
            'prioritas_id'
        );
    }

    public function files()
    {
        return $this->hasMany(
            ModelPrioritasBuktiFile::class,
            'file_bukti_id',
            'bukti_id'
        );
    }
}