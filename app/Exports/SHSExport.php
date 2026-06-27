<?php

namespace App\Exports;

use App\Models\ModelSHS;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SHSExport implements FromCollection, WithHeadings
{
    protected $field;
    protected $status;

    public function __construct($field, $status)
    {
        $this->field = $field ?? [];
        $this->status = $status;
    }

    public function collection()
    {
        $query = ModelSHS::select($this->field);

        if ($this->status) {
            $query->where('shs_status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return $this->field;
    }
}