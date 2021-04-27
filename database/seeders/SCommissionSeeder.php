<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commission = factory(App\Models\Policy\SCommission::class, 20)->create();
    }
}
