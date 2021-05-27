<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Crm\CCaseStage;

class CCaseStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caseStage = CCaseStage::factory()->create([
            'description' => 'Oportunidad perdida',
            'c_type_case_id' => '2'
        ]);
        $caseStage = CCaseStage::factory()->create([
            'description' => 'Oportunidad ganada',
            'c_type_case_id' => '2'
        ]);
        $caseStage = CCaseStage::factory()->create([
            'description' => 'Abierto',
            'c_type_case_id' => '1'
        ]);
        $caseStage = CCaseStage::factory()->create([
            'description' => 'Cerrado',
            'c_type_case_id' => '1'
        ]);
    }
}
