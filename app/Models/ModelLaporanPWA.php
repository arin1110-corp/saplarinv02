<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelDataPWA;

class ModelLaporanPWA extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'saplarin_laporan_pwa';
    protected $primaryKey = 'laporan_pwa_id';
    protected $fillable = ['laporan_pwa_data_pwa', 'laporan_pwa_tahun', 'laporan_pwa_keterangan', 'laporan_pwa_nominal', 'laporan_pwa_file'];
    public function datapwa()
    {
        return $this->belongsTo(ModelDataPWA::class, 'laporan_pwa_datapwa');
    }
}