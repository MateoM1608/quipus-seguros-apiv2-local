<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = factory(App\Models\GCountry::class)->create([
            'description' => 'Colombia',
            'initials' => 'COL'
        ]);

        $country = factory(App\Models\GCountry::class, 19)->create();
    }
}
