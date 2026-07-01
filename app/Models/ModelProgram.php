<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProgram extends Model
{
    use HasFactory;

    protected $table = 'saplarin_program';
    protected $primaryKey = 'program_id';
    protected $fillable = [
        'program_uid',
        'program_kode',
        'program_nama',
        'program_status',
    ];
    public function kegiatan()
    {
        return $this->hasMany(
            ModelKegiatan::class,
            'kegiatan_program_id',
            'program_id'
        );
    }
}