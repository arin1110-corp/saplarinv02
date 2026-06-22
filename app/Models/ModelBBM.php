<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelBBM extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_bbm_pengajuan';
    protected $primaryKey = 'bbm_id';

    protected $fillable = [
        'bbm_uid',

        'bbm_pengaju_id',
        'bbm_pengaju_nama',
        'bbm_pengaju_nip',
        'bbm_pengaju_email',
        'bbm_bidang_nama',

        'bbm_no_plat',
        'bbm_uraian_kegiatan',
        'bbm_liter',

        'bbm_spt_file',
        'bbm_spt_sync',

        'bbm_acc_pimpinan_file',
        'bbm_acc_pimpinan_sync',

        'bbm_status_pengajuan',

        'bbm_tanggal_nota',
        'bbm_laporan_nota_file',
        'bbm_laporan_nota_sync',

        'bbm_status_laporan',
        'bbm_catatan_admin',
        'bbm_foto_mobil_file',
        'bbm_foto_mobil_sync',
    ];

    protected $casts = [
        'bbm_liter' => 'decimal:2',
        'bbm_tanggal_nota' => 'date',
        'bbm_spt_sync' => 'boolean',
        'bbm_acc_pimpinan_sync' => 'boolean',
        'bbm_laporan_nota_sync' => 'boolean',
        'bbm_foto_mobil_sync' => 'boolean',
    ];
}