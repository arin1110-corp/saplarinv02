<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelBidang extends Model
{
    //
    protected $table = 'saplarin_bidang';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;
    protected $fillable = [
        'bidang_id',
        'bidang_nama',
        'bidang_instansi',
        'bidang_status',
    ];
}