<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSPJBukuTamu extends Model
{
    protected $table = 'saplarin_spj_buku_tamu';

    protected $primaryKey = 'buku_tamu_id';

    protected $fillable = ['buku_tamu_uid', 'spj_uid', 'buku_tamu_nama', 'buku_tamu_nip', 'buku_tamu_unit', 'buku_tamu_tujuan', 'buku_tamu_file', 'buku_tamu_waktu', 'buku_tamu_ip', 'buku_tamu_user_agent'];

    protected $casts = [
        'buku_tamu_waktu' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | SPJ
    |--------------------------------------------------------------------------
    */

    public function spj()
    {
        return $this->belongsTo(ModelSPJRealisasi::class, 'spj_uid', 'spj_uid');
    }
}