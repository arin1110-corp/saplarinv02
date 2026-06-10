<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelProgramPrioritasRencana extends Model
{
    use SoftDeletes;

    protected $table = 'sadarin_program_prioritas_rencana';
    protected $primaryKey = 'rencana_id';

    protected $fillable = [
        'rencana_uid',
        'rencana_prioritas_id',
        'rencana_judul',
        'rencana_target',
        'rencana_user_id',
        'rencana_user_nama',
        'rencana_user_nip',
        'rencana_bidang_id',
        'rencana_bidang_nama',
        'rencana_status',
    ];

    public function prioritas()
    {
        return $this->belongsTo(
            ModelProgramPrioritas::class,
            'rencana_prioritas_id',
            'prioritas_id'
        );
    }

    public function capaian()
    {
        return $this->hasMany(
            ModelProgramPrioritasCapaian::class,
            'capaian_rencana_id',
            'rencana_id'
        );
    }
}