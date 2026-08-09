<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelPadJenis extends Model
{
    protected $table = 'saplarin_pad_jenis';

    protected $primaryKey = 'pad_jenis_id';

    protected $fillable = [
        'pad_jenis_uid',
        'pad_jenis_kode',
        'pad_jenis_nama',
        'pad_jenis_keterangan',
        'pad_jenis_status',
    ];

    protected $casts = [
        'pad_jenis_status' => 'boolean',
    ];

    public function komponen(): HasMany
    {
        return $this->hasMany(
            ModelPadKomponen::class,
            'pad_komponen_jenis',
            'pad_jenis_id'
        );
    }

    public function target(): HasMany
    {
        return $this->hasMany(
            ModelPadTarget::class,
            'pad_target_jenis',
            'pad_jenis_id'
        );
    }
}