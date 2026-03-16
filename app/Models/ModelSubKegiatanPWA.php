<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ModelDataPWA;


class ModelSubKegiatanPWA extends Model
{
    use HasFactory;
    protected $table = 'saplarin_subkegiatan_pwa';
    protected $primaryKey = 'subkegiatan_pwa_id';
    protected $fillable = ['subkegiatan_pwa_nama', 'subkegiatan_pwa_pagu'];
    public function datapwa()
    {
        return $this->hasMany(ModelDataPWA::class, 'data_pwa_subkegiatan');
    }
}