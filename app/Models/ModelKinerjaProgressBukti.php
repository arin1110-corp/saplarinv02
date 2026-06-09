<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelKinerjaProgressBukti extends Model
{
    protected $table = 'saplarin_kinerja_progress_bukti';
    protected $primaryKey = 'bukti_id';

    protected $fillable = [
        'bukti_progress_id',
        'bukti_file',
        'bukti_nama_file',
    ];

    public function progress()
    {
        return $this->belongsTo(
            ModelKinerjaProgress::class,
            'bukti_progress_id',
            'progress_id'
        );
    }
}