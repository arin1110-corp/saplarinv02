<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelSHSReferensiHarga extends Model
{
    protected $table = 'saplarin_shs_referensi_harga';

    protected $primaryKey = 'shs_referensi_id';

    protected $fillable = ['shs_referensi_uid', 'shs_id', 'shs_referensi_harga', 'shs_referensi_link'];

    protected $casts = [
        'shs_referensi_harga' => 'decimal:2',
    ];

    /**
     * Relasi ke SHS
     */
    public function shs(): BelongsTo
    {
        return $this->belongsTo(ModelSHS::class, 'shs_id', 'shs_id');
    }
}