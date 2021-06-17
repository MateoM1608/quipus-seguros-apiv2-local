<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, Exportable, WithHeadings};

class SClientExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $data;

    public function __construct($data) {
        $this->data = $data;
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
        return [
            'Cedula/Nit',
            'Nombres',
            'Apellidos',
            'Fecha de Nacimiento',
            'Direccion',
            'Telefono',
            'Celular',
            'CorreoE',
            'Ciudad',
            'Tipo de Identificacion',
            'Observaciones',
            'Status',
        ];
    }
}
