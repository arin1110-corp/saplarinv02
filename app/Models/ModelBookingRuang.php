<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelBookingRuang extends Model
{
    protected $table = 'saplarin_booking_ruang';

    protected $primaryKey = 'booking_id';

    protected $fillable = [

        'booking_uid',

        'booking_ruang_id',

        'booking_tanggal',

        'booking_jam_mulai',

        'booking_jam_selesai',

        'booking_peruntukan',

        'booking_surat',

        'booking_catatan',

        'booking_status',

        'booking_created_by',

        'booking_created_by_nama',

        'booking_created_by_nip',

        'booking_created_by_unit',

        'booking_verifikator',

        'booking_verifikasi_at',

        'booking_catatan_admin',

    ];

    protected $casts = [

        'booking_tanggal' => 'date',

        'booking_verifikasi_at' => 'datetime',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (empty($model->booking_uid)) {

                $model->booking_uid = (string) Str::uuid();

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function ruang()
    {
        return $this->belongsTo(
            ModelRuang::class,
            'booking_ruang_id',
            'ruang_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query->whereIn('booking_status', [

            'Menunggu',

            'Disetujui',

        ]);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate(
            'booking_tanggal',
            now()->toDateString()
        );
    }

    public function scopeRuang($query, $ruangId)
    {
        return $query->where(
            'booking_ruang_id',
            $ruangId
        );
    }

    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate(
            'booking_tanggal',
            $tanggal
        );
    }
}