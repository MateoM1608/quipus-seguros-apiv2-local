<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CCaseStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Negocio pendiente',
            'c_type_case_id' => '2'
        ]);
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Negocio finalizado',
            'c_type_case_id' => '2'
        ]);
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Negocio gestionado',
            'c_type_case_id' => '2'
        ]);
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Negocio para reprogramar',
            'c_type_case_id' => '2'
        ]);
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Abierto',
            'c_type_case_id' => '1'
        ]);
        $caseStage = factory(App\Models\Crm\CCaseStage::class)->create([
            'description' => 'Cerrado',
            'c_type_case_id' => '1'
        ]);
    }
}
