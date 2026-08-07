<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelRuang extends Model
{
    protected $table = 'saplarin_ruang';

    protected $primaryKey = 'ruang_id';

    protected $fillable = [

        'ruang_uid',

        'ruang_nama',

        'ruang_lokasi',

        'ruang_kapasitas',

        'ruang_keterangan',

        'ruang_status',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->ruang_uid)) {

                $model->ruang_uid = (string) Str::uuid();

            }

        });
    }

    public function booking()
    {
        return $this->hasMany(
            ModelBookingRuang::class,
            'booking_ruang_id',
            'ruang_id'
        );
    }
}