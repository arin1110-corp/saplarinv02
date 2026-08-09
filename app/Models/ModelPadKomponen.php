<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelPadKomponen extends Model
{
    protected $table = 'saplarin_pad_komponen';

    protected $primaryKey = 'pad_komponen_id';

    protected $fillable = ['pad_komponen_uid', 'pad_komponen_jenis', 'pad_komponen_kode', 'pad_komponen_nama', 'pad_komponen_keterangan', 'pad_komponen_status'];

    protected $casts = [
        'pad_komponen_status' => 'boolean',
    ];

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(ModelPadJenis::class, 'pad_komponen_jenis', 'pad_jenis_id');
    }

    public function target(): HasMany
    {
        return $this->hasMany(ModelPadTarget::class, 'pad_target_komponen', 'pad_komponen_id');
    }
    public function subkomponen()
    {
        return $this->hasMany(ModelPadSubkomponen::class, 'pad_subkomponen_komponen', 'pad_komponen_id');
    }
}