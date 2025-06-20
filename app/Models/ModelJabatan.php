<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelJabatan extends Model
{
    //
    protected $table = 'saplarin_jabatan';
    protected $primaryKey = 'jabatan_id';
    public $timestamps = false;
    protected $fillable = [
        'jabatan_id',
        'jabatan_nama',
        'jabatan_status',
    ];
}