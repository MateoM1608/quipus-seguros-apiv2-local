<?php

use Illuminate\Database\Seeder;

class GCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Pereira',
            'initials' => 'PEI',
            'g_country_id' => '1',
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Dosquebradas',
            'initials' => 'DDAS',
            'g_country_id' => '1', 
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Manizales',
            'initials' => 'MLZ',
            'g_country_id' => '1',  
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Armenia',
            'initials' => 'ARM',
            'g_country_id' => '1',  
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Cartago',
            'initials' => 'CTO',
            'g_country_id' => '1',   
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'La Virginia',
            'initials' => 'LNA',
            'g_country_id' => '1', 
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Cali',
            'initials' => 'CLI',
            'g_country_id' => '1', 
        ]);
        $city = factory(App\Models\GCity::class)->create([
            'description' => 'Medellin',
            'initials' => 'MLN',
            'g_country_id' => '1', 
        ]);
        $city = factory(App\Models\GCity::class)->create([          
            'description' => 'Tulua',
            'initials' => 'TLA',
            'g_country_id' => '1'         
        ]);

        $country = factory(App\Models\GCity::class, 11)->create();
    }
}
