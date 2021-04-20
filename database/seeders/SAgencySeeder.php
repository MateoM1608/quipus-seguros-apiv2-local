<?php

use Illuminate\Database\Seeder;

class SAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agencies = factory(App\Models\Policy\SAgency::class)->create([
            'agency_name' => 'Amparalo',
            'identification' => rand(816003186, 9000000),
            'agency_commission' => '10'
        ]);
        $agencies = factory(App\Models\Policy\SAgency::class)->create([
            'agency_name' => 'Charria seguros',
            'identification' => rand(816003186, 9000000),
            'agency_commission' => '7'
        ]);
        $agencies = factory(App\Models\Policy\SAgency::class)->create([
            'agency_name' => 'Sano y Salvo',
            'identification' => rand(816003186, 9000000),
            'agency_commission' => '5'
        ]);
        $agencies = factory(App\Models\Policy\SAgency::class)->create([
            'agency_name' => 'Primero Seguros',
            'identification' => rand(816003186, 9000000),
            'agency_commission' => '5'
        ]);
    }
}
