<?php

namespace App\Imports;

use App\Models\ModelSHSKelompok;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class SHSKelompokImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        dd('MASUK IMPORT', $rows->count());
    }
}