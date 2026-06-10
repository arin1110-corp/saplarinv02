<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelProgramPrioritasCapaian extends Model
{
    use SoftDeletes;

    protected $table = 'sadarin_program_prioritas_capaian';
    protected $primaryKey = 'capaian_id';

    protected $fillable = [
        'capaian_uid',
        'capaian_rencana_id',
        'capaian_judul',
        'capaian_deskripsi',
        'capaian_tanggal_mulai',
        'capaian_jumlah',
        'capaian_tanggal_selesai',
        'capaian_user_id',
        'capaian_user_nama',
        'capaian_user_nip',
        'capaian_bidang_id',
        'capaian_bidang_nama',
        'capaian_status',
    ];

    protected $casts = [
        'capaian_tanggal_mulai' => 'date',
        'capaian_tanggal_selesai' => 'date',
    ];

    public function rencana()
    {
        return $this->belongsTo(
            ModelProgramPrioritasRencana::class,
            'capaian_rencana_id',
            'rencana_id'
        );
    }

    public function files()
    {
        return $this->hasMany(
            ModelProgramPrioritasCapaianFile::class,
            'file_capaian_id',
            'capaian_id'
        );
    }
}