<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSubKegiatan extends Model
{
    use HasFactory;
    protected $table = 'saplarin_sub_kegiatan';
    protected $primaryKey = 'sub_kegiatan_id';
    protected $fillable = [
        'sub_kegiatan_uid',
        'sub_kegiatan_kegiatan',
        'sub_kegiatan_nama',
        'sub_kegiatan_status',
    ];
}