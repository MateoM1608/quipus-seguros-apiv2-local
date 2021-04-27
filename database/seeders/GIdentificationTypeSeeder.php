<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GIdentificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Cedula de Ciudadania',
            'alias' => 'CC'
        ]);
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Nit',
            'alias' => 'NIT'
        ]);
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Cedula de Extranjeria',
            'alias' => 'CE'
        ]);
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Tarjeta de Identidad',
            'alias' => 'TI'
        ]);
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Registro Civil',
            'alias' => 'RC'
        ]);
        $identificationType = factory(App\Models\GIdentificationType::class)->create([
            'description' => 'Documento Extranjero',
            'alias' => 'DE'
        ]);

    }
}
