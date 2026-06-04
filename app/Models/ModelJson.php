<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelJson extends Model
{
    protected $table = 'saplarin_json';
    protected $primaryKey = 'json_id';

    protected $fillable = [
        'json_nama',
        'json_file',
        'json_status',
    ];
}