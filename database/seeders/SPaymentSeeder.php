<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Payment = factory(App\Models\Policy\SPayment::class, 20)->create();
    }
}
