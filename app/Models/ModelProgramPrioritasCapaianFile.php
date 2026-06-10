<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelProgramPrioritasCapaianFile extends Model
{
    protected $table = 'sadarin_program_prioritas_capaian_file';
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'file_capaian_id',
        'file_path',
        'file_nama_asli',
    ];

    public function capaian()
    {
        return $this->belongsTo(
            ModelProgramPrioritasCapaian::class,
            'file_capaian_id',
            'capaian_id'
        );
    }
}