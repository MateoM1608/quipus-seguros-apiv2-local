<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\GCountry;

class GCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = GCountry::factory()->create([
            'description' => 'Colombia',
            'initials' => 'COL'
        ]);
    }
}
