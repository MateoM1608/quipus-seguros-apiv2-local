<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\GCity;

class GCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = GCity::factory()->create([
            'description' => 'Pereira',
            'initials' => 'PEI',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Dosquebradas',
            'initials' => 'DDAS',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Manizales',
            'initials' => 'MLZ',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Armenia',
            'initials' => 'ARM',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Cartago',
            'initials' => 'CTO',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'La Virginia',
            'initials' => 'LNA',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Cali',
            'initials' => 'CLI',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Medellin',
            'initials' => 'MLN',
            'g_country_id' => '1',
        ]);

        $city = GCity::factory()->create([
            'description' => 'Tulua',
            'initials' => 'TLA',
            'g_country_id' => '1'
        ]);
    }
}
