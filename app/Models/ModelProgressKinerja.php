<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelProgressKinerja extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_progress_kinerja';
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'progress_uid',

        'progress_bidang_id',
        'progress_bidang_nama',

        'progress_kegiatan',
        'progress_deskripsi',

        'progress_tanggal_mulai',
        'progress_tanggal_selesai',

        'progress_persentase',

        'progress_user_tanggal_mulai',
        'progress_user_tanggal_selesai',
        'progress_user_persentase',

        'progress_status',

        'progress_created_by',
        'progress_created_by_nama',

        'progress_updated_by',
        'progress_updated_by_nama',
    ];

    protected $casts = [
        'progress_tanggal_mulai' => 'date',
        'progress_tanggal_selesai' => 'date',
        'progress_user_tanggal_mulai' => 'date',
        'progress_user_tanggal_selesai' => 'date',
        'progress_persentase' => 'decimal:2',
        'progress_user_persentase' => 'decimal:2',
    ];

    public function buktis()
    {
        return $this->hasMany(
            ModelProgressBukti::class,
            'bukti_progress_id',
            'progress_id'
        );
    }

    public function buktiAdmin()
    {
        return $this->hasMany(
            ModelProgressBukti::class,
            'bukti_progress_id',
            'progress_id'
        )->where('bukti_tipe', 'admin');
    }

    public function buktiUser()
    {
        return $this->hasMany(
            ModelProgressBukti::class,
            'bukti_progress_id',
            'progress_id'
        )->where('bukti_tipe', 'user');
    }
}