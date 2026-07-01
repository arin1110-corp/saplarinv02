<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKegiatan extends Model
{
    use HasFactory;
    protected $table = 'saplarin_kegiatan';
    protected $primaryKey = 'kegiatan_id';
    protected $fillable = [
        'kegiatan_uid',
        'kegiatan_kode',
        'kegiatan_program',
        'kegiatan_nama',
        'kegiatan_status',
    ];
    public function program()
    {
        return $this->belongsTo(
            ModelProgram::class,
            'kegiatan_program',
            'program_id'
        );
    }

    public function subKegiatan()
    {
        return $this->hasMany(
            ModelSubKegiatan::class,
            'sub_kegiatan_kegiatan',
            'kegiatan_id'
        );
    }
}