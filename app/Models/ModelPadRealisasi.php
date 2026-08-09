<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPadRealisasi extends Model
{
    use HasFactory;

    protected $table = 'saplarin_pad_realisasi';

    protected $primaryKey = 'pad_realisasi_id';

    protected $fillable = ['pad_realisasi_uid', 'pad_realisasi_target', 'pad_realisasi_subkomponen', 'pad_realisasi_tanggal', 'pad_realisasi_nominal', 'pad_realisasi_keterangan', 'pad_realisasi_dokumen', 'pad_realisasi_input', 'pad_realisasi_input_nama', 'pad_realisasi_unit', 'pad_realisasi_status'];

    protected $casts = [
        'pad_realisasi_tanggal' => 'date',

        'pad_realisasi_nominal' => 'decimal:2',
    ];

    public function target()
    {
        return $this->belongsTo(ModelPadTarget::class, 'pad_realisasi_target', 'pad_target_id');
    }

    public function subkomponen()
    {
        return $this->belongsTo(ModelPadSubkomponen::class, 'pad_realisasi_subkomponen', 'pad_subkomponen_id');
    }
}