<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class COperationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operationType = factory(App\Models\Crm\COperationType::class)->create([
            'operation_type' => 'Tarea'
        ]);
        $operationType = factory(App\Models\Crm\COperationType::class)->create([
            'operation_type' => 'Llamada'
        ]);
        $operationType = factory(App\Models\Crm\COperationType::class)->create([
            'operation_type' => 'Correo'
        ]);
        $operationType = factory(App\Models\Crm\COperationType::class)->create([
            'operation_type' => 'Contacto'
        ]);
    }
}
