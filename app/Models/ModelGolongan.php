<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelGolongan extends Model
{
    //
    protected $table = 'saplarin_golongan';
    protected $primaryKey = 'golongan_id';
    public $timestamps = false;
    protected $fillable = [
        'golongan_id',
        'golongan_nama',
        'golongan_pangkat',
        'golongan_status',
    ];
}