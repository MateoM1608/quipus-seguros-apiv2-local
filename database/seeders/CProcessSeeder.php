<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $process = factory(App\Models\Crm\CProcess::class, 20)->create();
    }
}
