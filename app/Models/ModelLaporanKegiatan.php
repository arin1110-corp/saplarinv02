<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelLaporanKegiatan extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_laporan_kegiatan';
    protected $primaryKey = 'laporan_kegiatan_id';

    protected $fillable = [
        'laporan_kegiatan_uid',
        'laporan_kegiatan_tahun',
        'laporan_kegiatan_nama',
        'laporan_kegiatan_sub_kegiatan_id',
        'laporan_kegiatan_sub_kegiatan_nama',
        'laporan_kegiatan_deskripsi',
        'laporan_kegiatan_bidang_id',
        'laporan_kegiatan_bidang_nama',
        'laporan_kegiatan_user_id',
        'laporan_kegiatan_user_nama',
        'laporan_kegiatan_user_nip',
        'laporan_kegiatan_status',
    ];

    public function aktivitas()
    {
        return $this->hasMany(
            ModelLaporanAktivitas::class,
            'aktivitas_kegiatan_id',
            'laporan_kegiatan_id'
        );
    }
}