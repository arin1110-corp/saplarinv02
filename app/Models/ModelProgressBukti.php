<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelProgressBukti extends Model
{
    protected $table = 'saplarin_progress_bukti';
    protected $primaryKey = 'bukti_id';

    protected $fillable = [
        'bukti_progress_id',
        'bukti_file',
        'bukti_nama_file',
        'bukti_tipe',
        'bukti_upload_by',
        'bukti_upload_by_nama',
    ];

    public function progress()
    {
        return $this->belongsTo(
            ModelProgressKinerja::class,
            'bukti_progress_id',
            'progress_id'
        );
    }
}