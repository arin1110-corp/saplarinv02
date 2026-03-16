<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelSubKegiatanPWA;
use App\Models\ModelLaporanPWA;

class ModelDataPWA extends Model
{
    use HasFactory;
    protected $table = 'saplarin_data_pwa';
    protected $primaryKey = 'data_pwa_id';
    protected $fillable = ['data_pwa_subkegiatan', 'data_pwa_tahun', 'data_pwa_pagu', 'data_pwa_realisasi'];
    public function subkegiatan()
    {
        return $this->belongsTo(ModelSubKegiatanPWA::class, 'data_pwa_subkegiatan');
    }

    public function laporan()
    {
        return $this->hasMany(ModelLaporanPWA::class, 'laporan_pwa_data_pwa');
    }
}