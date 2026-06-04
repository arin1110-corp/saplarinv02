<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelDriveFolder extends Model
{
    protected $table = 'saplarin_drive_folder';
    protected $primaryKey = 'folder_id';

    protected $fillable = [
        'folder_nama',
        'folder_prefix',
        'folder_drive_id',
        'folder_json',
        'folder_status',
    ];

    public function json()
    {
        return $this->belongsTo(
            ModelJson::class,
            'folder_json',
            'json_id'
        );
    }
}