<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelKinerjaProgress extends Model
{
    protected $table = 'saplarin_kinerja_progress';
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'progress_uid',
        'progress_kinerja_id',
        'progress_tanggal_mulai',
        'progress_tanggal_selesai',
        'progress_triwulan',
        'progress_persentase',
        'progress_keterangan',
        'progress_status',
        'progress_catatan_verifikasi',
        'progress_verified_by',
        'progress_verified_by_nama',
        'progress_verified_at',
        'progress_user_id',
        'progress_user_nama',
        'progress_user_nip',
        'progress_bidang_id',
        'progress_bidang_nama',
    ];

    protected $casts = [
        'progress_tanggal_mulai' => 'date',
        'progress_tanggal_selesai' => 'date',
        'progress_persentase' => 'decimal:2',
        'progress_verified_at' => 'datetime',
    ];

    public function kinerja()
    {
        return $this->belongsTo(ModelKinerja::class, 'progress_kinerja_id', 'kinerja_id');
    }

    public function bukti()
    {
        return $this->hasMany(ModelKinerjaProgressBukti::class, 'bukti_progress_id', 'progress_id');
    }
}