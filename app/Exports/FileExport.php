<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, Exportable, WithHeadings};

class FileExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $data;
    private $headings;

    public function __construct($data, array $headings) {
        $this->data = $data;
        $this->headings = $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
