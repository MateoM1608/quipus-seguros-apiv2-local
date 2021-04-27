<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $policy = factory(App\Models\Policy\SRisk::class, 20)->create();
    }
}
