<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSHS extends Model
{
    protected $table = 'saplarin_shs';

    protected $primaryKey = 'shs_id';

    protected $fillable = ['shs_uid', 'shs_tahun', 'shs_unit_kode', 'shs_unit_nama', 'shs_kode_kelompok', 'shs_kelompok_barang', 'shs_barang', 'shs_spesifikasi', 'shs_satuan', 'shs_harga', 'shs_tkdn', 'shs_link_survei', 'shs_kelompok', 'shs_status', 'shs_merek', 'shs_tipe', 'shs_dasar_usulan', 'shs_keterangan', 'shs_catatan_admin', 'shs_verifikasi_at', 'shs_verifikasi_nama', 'shs_verifikasi_nip', 'shs_verifikasi_jabatan', 'shs_verifikasi_bidang', 'shs_operator_id', 'shs_operator_nama', 'shs_operator_nip'];

    protected $casts = [
        'shs_verifikasi_at' => 'datetime',
    ];
}