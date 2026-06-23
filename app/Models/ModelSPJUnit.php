<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSPJUnit extends Model
{
    protected $table = 'saplarin_spj_unit';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_uid',
        'unit_kode',
        'unit_nama',
        'unit_status',
    ];
}