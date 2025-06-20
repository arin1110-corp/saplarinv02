<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelInstansi extends Model
{
    //
    protected $table = 'saplarin_instansi';
    protected $primaryKey = 'instansi_id';
    public $timestamps = false;
    protected $fillable = [
        'instansi_id',
        'instansi_nama',
        'instansi_status',
    ];
}