<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSHSKelompok extends Model
{
    protected $table = 'saplarin_shs_kelompok';

    protected $primaryKey = 'kelompok_id';

    protected $fillable = ['kelompok_uid', 'kelompok_kode', 'kelompok_nama','kelompok_tipe', 'kelompok_status', 'created_by', 'updated_by'];
    public function shs()
    {
        return $this->hasMany(ModelSHS::class, 'shs_kode_kelompok', 'kelompok_kode');
    }
}