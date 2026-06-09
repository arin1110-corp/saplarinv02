<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelKinerja extends Model
{
    use SoftDeletes;

    protected $table = 'saplarin_kinerja';
    protected $primaryKey = 'kinerja_id';

    protected $fillable = ['kinerja_uid', 'kinerja_tahun', 'kinerja_bidang_id', 'kinerja_bidang_nama', 'kinerja_kegiatan', 'kinerja_deskripsi', 'kinerja_status', 'kinerja_created_by', 'kinerja_created_by_nama'];

    public function progress()
    {
        return $this->hasMany(ModelKinerjaProgress::class, 'progress_kinerja_id', 'kinerja_id');
    }

    public function progressTerbaru()
    {
        return $this->hasOne(ModelKinerjaProgress::class, 'progress_kinerja_id', 'kinerja_id')->latest('created_at');
    }
    public function progressTerverifikasi()
    {
        return $this->hasMany(ModelKinerjaProgress::class, 'progress_kinerja_id', 'kinerja_id')->where('progress_status', 'Diterima');
    }
}