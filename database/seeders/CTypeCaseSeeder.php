<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CTypeCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeCase = factory(App\Models\Crm\CTypeCase::class)->create([
            'description' => 'Servicio al cliente'
        ]);

        $typeCase = factory(App\Models\Crm\CTypeCase::class)->create([
            'description' => 'Oportunidades de Negocio'
        ]);

    }
}
