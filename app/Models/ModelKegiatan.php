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
        'kegiatan_program',
        'kegiatan_nama',
        'kegiatan_status',
    ];
}