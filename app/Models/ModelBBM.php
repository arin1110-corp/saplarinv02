<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelBBM extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_bbm_pengajuan';
    protected $primaryKey = 'bbm_id';

    protected $fillable = ['bbm_uid', 'bbm_pengaju_id', 'bbm_pengaju_nama', 'bbm_pengaju_nip', 'bbm_bidang_nama', 'bbm_sub_kegiatan_id', 'bbm_uraian_kegiatan', 'bbm_liter', 'bbm_spt_file', 'bbm_spt_sync', 'bbm_acc_pimpinan_file', 'bbm_acc_pimpinan_sync', 'bbm_status_pengajuan', 'bbm_tanggal_nota', 'bbm_laporan_nota_file', 'bbm_laporan_nota_sync', 'bbm_status_laporan', 'bbm_catatan_admin'];

    protected $casts = [
        'bbm_liter' => 'decimal:2',

        'bbm_spt_sync' => 'boolean',
        'bbm_acc_pimpinan_sync' => 'boolean',
        'bbm_laporan_nota_sync' => 'boolean',
    ];

    public function subKegiatan()
    {
        return $this->belongsTo(ModelSubKegiatan::class, 'bbm_sub_kegiatan_id', 'sub_kegiatan_id');
    }
}