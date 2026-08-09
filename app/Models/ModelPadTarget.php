<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPadTarget extends Model
{
    protected $table = 'saplarin_pad_target';

    protected $primaryKey = 'pad_target_id';

    protected $fillable = [

        'pad_target_uid',

        'pad_target_tahun',

        'pad_target_jenis',

        'pad_target_komponen',

        'pad_target_unit',

        'pad_target_unit_nama',

        'pad_target_unit_kode',

        'pad_target_nominal',

        'pad_target_rencana',

        'pad_target_keterangan',

        'pad_target_status',

    ];


    protected $casts = [

        'pad_target_tahun' => 'integer',

        'pad_target_nominal' => 'decimal:2',

        'pad_target_rencana' => 'decimal:2',

        'pad_target_status' => 'boolean',

    ];


    public function jenis()
    {
        return $this->belongsTo(
            ModelPadJenis::class,
            'pad_target_jenis',
            'pad_jenis_id'
        );
    }


    public function komponen()
    {
        return $this->belongsTo(
            ModelPadKomponen::class,
            'pad_target_komponen',
            'pad_komponen_id'
        );
    }


    public function realisasi()
    {
        return $this->hasMany(
            ModelPadRealisasi::class,
            'pad_realisasi_target',
            'pad_target_id'
        );
    }


    public function getTotalRealisasiAttribute()
    {
        return $this->realisasi
            ->sum('pad_realisasi_nominal');
    }


    public function getSisaRealisasiAttribute()
    {
        return max(
            0,
            (float) $this->pad_target_nominal
                - (float) $this->total_realisasi
        );
    }


    public function getPersentaseRealisasiAttribute()
    {
        if ((float) $this->pad_target_nominal <= 0) {
            return 0;
        }

        return (
            $this->total_realisasi
            / $this->pad_target_nominal
        ) * 100;
    }
}