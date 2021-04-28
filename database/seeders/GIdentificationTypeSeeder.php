<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\GIdentificationType;

class GIdentificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Cedula de Ciudadania',
            'alias' => 'CC'
        ]);
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Nit',
            'alias' => 'NIT'
        ]);
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Cedula de Extranjeria',
            'alias' => 'CE'
        ]);
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Tarjeta de Identidad',
            'alias' => 'TI'
        ]);
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Registro Civil',
            'alias' => 'RC'
        ]);
        $identificationType = GIdentificationType::factory()->create([
            'description' => 'Documento Extranjero',
            'alias' => 'DE'
        ]);

    }
}
