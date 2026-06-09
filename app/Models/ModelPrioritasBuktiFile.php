<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPrioritasBuktiFile extends Model
{
    protected $table = 'saplarin_prioritas_bukti_file';
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'file_bukti_id',
        'file_path',
        'file_nama_asli',
    ];

    public function bukti()
    {
        return $this->belongsTo(
            ModelPrioritasBukti::class,
            'file_bukti_id',
            'bukti_id'
        );
    }
}