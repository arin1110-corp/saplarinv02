<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSHSSatuan extends Model
{
    protected $table = 'saplarin_shs_satuan';

    protected $primaryKey = 'satuan_id';

    protected $fillable = [

        'satuan_uid',

        'satuan_nama',

        'satuan_status',

        'created_by',

        'updated_by'

    ];
}