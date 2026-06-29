<?php

namespace App\Exports;

use App\Models\ModelSHS;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SHSExport implements FromCollection, WithHeadings, WithMapping
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
    public function map($row): array
    {
        $data = [];

        foreach ($this->field as $field) {
            $value = $row->{$field};

            if ($field === 'shs_link_survei') {
                $links = json_decode($value, true);

                $value = is_array($links) ? implode("\n", $links) : $value;
            }

            $data[] = $value;
        }

        return $data;
    }
}