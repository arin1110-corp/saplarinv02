<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatanLaporan extends Model
{
    protected $table = 'saplarin_sub_kegiatan_laporan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'laporan_uid',
        'laporan_unit_kode',
        'laporan_unit_nama',
        'laporan_sub_kegiatan_id',
        'laporan_bulan',
        'laporan_tahun',
        'laporan_status',
        'laporan_created_by',
        'laporan_created_by_nama',
        'laporan_created_by_nip',
        // tambahkan ini
        'laporan_catatan_admin',
        'laporan_catatan_at',
        'laporan_catatan_by',
    ];
    public function subKegiatan()
    {
        return $this->belongsTo(ModelSubKegiatan::class, 'laporan_sub_kegiatan_id', 'sub_kegiatan_id');
    }

    public function detail()
    {
        return $this->hasMany(SubKegiatanLaporanDetail::class, 'detail_laporan_id', 'laporan_id');
    }

    public function permasalahan()
    {
        return $this->hasMany(SubKegiatanPermasalahan::class, 'permasalahan_laporan_id', 'laporan_id');
    }

    public function solusi()
    {
        return $this->hasMany(SubKegiatanSolusi::class, 'solusi_laporan_id', 'laporan_id');
    }

    public function tindakLanjut()
    {
        return $this->hasMany(SubKegiatanTindakLanjut::class, 'tindak_lanjut_laporan_id', 'laporan_id');
    }

    public function indikator()
    {
        return $this->hasMany(SubKegiatanLaporanDetail::class, 'detail_laporan_id', 'laporan_id');
    }
}