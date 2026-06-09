<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelLaporanAktivitas extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_laporan_aktivitas';
    protected $primaryKey = 'aktivitas_id';

    protected $fillable = [
        'aktivitas_uid',
        'aktivitas_kegiatan_id',
        'aktivitas_nama',
        'aktivitas_uraian',
        'aktivitas_tanggal_mulai',
        'aktivitas_tanggal_selesai',
        'aktivitas_triwulan',
        'aktivitas_user_id',
        'aktivitas_user_nama',
        'aktivitas_user_nip',
        'aktivitas_bidang_id',
        'aktivitas_bidang_nama',
        'aktivitas_status',
    ];

    protected $casts = [
        'aktivitas_tanggal_mulai' => 'date',
        'aktivitas_tanggal_selesai' => 'date',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(
            ModelLaporanKegiatan::class,
            'aktivitas_kegiatan_id',
            'laporan_kegiatan_id'
        );
    }

    public function bukti()
    {
        return $this->hasMany(
            ModelLaporanAktivitasBukti::class,
            'bukti_aktivitas_id',
            'aktivitas_id'
        );
    }
}