<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelLaporanAktivitasBukti extends Model
{
    protected $table = 'saplarin_laporan_aktivitas_bukti';
    protected $primaryKey = 'bukti_id';

    protected $fillable = [
        'bukti_aktivitas_id',
        'bukti_file',
        'bukti_nama_file',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(
            ModelLaporanAktivitas::class,
            'bukti_aktivitas_id',
            'aktivitas_id'
        );
    }
}