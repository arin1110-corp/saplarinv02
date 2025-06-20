<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelJabatanVerificator extends Model
{
    //
    protected $table = 'saplarin_jabatanverificator';
    protected $primaryKey = 'jabatanverificator_id';
    public $timestamps = false;
    protected $fillable = [
        'jabatanverificator_nama',
        'jabatanverificator_status',
    ];
}