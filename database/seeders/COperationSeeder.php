<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class COperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operation = factory(App\Models\Crm\COperation::class, 20)->create();
    }
}
