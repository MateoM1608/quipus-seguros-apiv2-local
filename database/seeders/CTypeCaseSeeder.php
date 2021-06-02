<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Crm\CTypeCase;

class CTypeCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeCase = CTypeCase::factory()->create([
            'description' => 'Servicio al cliente'
        ]);

        $typeCase = CTypeCase::factory()->create([
            'description' => 'Oportunidades de negocio'
        ]);

        $typeCase = CTypeCase::factory()->create([
            'description' => 'Gestión operativa'
        ]);

    }
}
