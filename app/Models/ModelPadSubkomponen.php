<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelPadSubkomponen extends Model
{
    protected $table = 'saplarin_pad_subkomponen';

    protected $primaryKey = 'pad_subkomponen_id';

    protected $fillable = [

        'pad_subkomponen_uid',

        'pad_subkomponen_komponen',

        'pad_subkomponen_kode',

        'pad_subkomponen_nama',

        'pad_subkomponen_keterangan',

        'pad_subkomponen_status',

    ];

    protected $casts = [

        'pad_subkomponen_status' => 'boolean',

    ];


    protected static function booted()
    {
        static::creating(function ($model) {

            if (!$model->pad_subkomponen_uid) {

                $model->pad_subkomponen_uid =
                    (string) Str::uuid();

            }

        });
    }


    public function komponen()
    {
        return $this->belongsTo(
            ModelPadKomponen::class,
            'pad_subkomponen_komponen',
            'pad_komponen_id'
        );
    }


    public function realisasi()
    {
        return $this->hasMany(
            ModelPadRealisasi::class,
            'pad_realisasi_subkomponen',
            'pad_subkomponen_id'
        );
    }
}