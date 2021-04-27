<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor = factory(App\Models\Policy\GVendor::class, 20)->create();
    }
}
