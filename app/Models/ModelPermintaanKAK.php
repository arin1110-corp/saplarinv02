<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelPermintaanKAK extends Model
{
    use HasFactory;

    protected $table = 'saplarin_permintaan_kak';
    protected $primaryKey = 'kak_id';

    protected $fillable = ['kak_uid', 'kak_sub_kegiatan_id', 'kak_tahun', 'kak_keterangan', 'kak_tahapan', 'kak_file', 'kak_status', 'kak_created_by', 'kak_created_by_nama'];

    protected $casts = [
        'kak_tahun' => 'integer',
        'kak_status' => 'integer',
    ];

    public function subKegiatan()
    {
        return $this->belongsTo(ModelSubKegiatan::class, 'kak_sub_kegiatan_id', 'sub_kegiatan_id');
    }

    public function kegiatan()
    {
        return $this->hasOneThrough(ModelKegiatan::class, ModelSubKegiatan::class, 'sub_kegiatan_id', 'kegiatan_id', 'kak_sub_kegiatan_id', 'sub_kegiatan_kegiatan');
    }

    public function program()
    {
        return $this->hasOneThrough(ModelProgram::class, ModelKegiatan::class, 'kegiatan_id', 'program_id', 'kegiatan_id', 'kegiatan_program');
    }
}