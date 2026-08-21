<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ModelStandarHargaPenggunaan;

class ModelStandarHarga extends Model
{
    protected $table = 'saplarin_standar_harga';

    protected $primaryKey = 'standar_harga_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['standar_harga_uid', 'standar_harga_jenis', 'standar_harga_tahun', 'standar_harga_kode_kelompok', 'standar_harga_uraian_kelompok', 'standar_harga_id_standar', 'standar_harga_kode_barang', 'standar_harga_uraian_barang', 'standar_harga_spesifikasi', 'standar_harga_satuan', 'standar_harga_satuan_harga', 'standar_harga_kode_rekening', 'standar_harga_status', 'standar_harga_input_nip', 'standar_harga_input_nama'];

    protected $casts = [
        'standar_harga_tahun' => 'integer',

        'standar_harga_satuan_harga' => 'decimal:2',

        'standar_harga_status' => 'boolean',
    ];
    public function penggunaan()
    {
        return $this->hasMany(ModelStandarHargaPenggunaan::class, 'penggunaan_standar_harga', 'standar_harga_id');
    }
}