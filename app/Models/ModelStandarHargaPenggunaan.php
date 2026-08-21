<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelStandarHargaPenggunaan extends Model
{
    protected $table = 'saplarin_standar_harga_penggunaan';

    protected $primaryKey = 'penggunaan_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [

        'penggunaan_uid',

        'penggunaan_standar_harga',

        'penggunaan_tahun',

        'penggunaan_input_nip',

        'penggunaan_input_nama',

        'penggunaan_unit',

        'penggunaan_status',

    ];

    protected $casts = [

        'penggunaan_tahun' => 'integer',

        'penggunaan_status' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | MASTER STANDAR HARGA
    |--------------------------------------------------------------------------
    */

    public function standarHarga()
    {
        return $this->belongsTo(
            ModelStandarHarga::class,
            'penggunaan_standar_harga',
            'standar_harga_id'
        );
    }
}