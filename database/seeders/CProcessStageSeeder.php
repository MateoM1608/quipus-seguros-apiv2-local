<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CProcessStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $processType = factory(App\Models\Crm\CProcessStage::class)->create([
            'process_stage' => 'Contacto Inicial'
        ]);
        $processType = factory(App\Models\Crm\CProcessStage::class)->create([
            'process_stage' => 'Cita Programada'
        ]);
        $processType = factory(App\Models\Crm\CProcessStage::class)->create([
            'process_stage' => 'Cotización Presentada'
        ]);
        $processType = factory(App\Models\Crm\CProcessStage::class)->create([
            'process_stage' => 'En Expedición'
        ]);
        $processType = factory(App\Models\Crm\CProcessStage::class)->create([
            'process_stage' => 'Expedida'
        ]);
    }
}
