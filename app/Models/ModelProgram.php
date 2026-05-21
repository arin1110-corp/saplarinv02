<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProgram extends Model
{
    use HasFactory;

    protected $table = 'saplarin_program';
    protected $primaryKey = 'program_id';
    protected $fillable = [
        'program_uid',
        'program_nama',
        'program_status',
    ];
}