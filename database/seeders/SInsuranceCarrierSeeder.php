<?php

use Illuminate\Database\Seeder;

class SInsuranceCarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insurance = factory(App\Models\Policy\SInsuranceCarrier::class)->create([
            'insurance_carrier' => 'Suramericana',
            'identification' => rand(81600000, 90000000)
        ]);
        $insurance = factory(App\Models\Policy\SInsuranceCarrier::class)->create([
            'insurance_carrier' => 'Allianz',
            'identification' => rand(81600000, 90000000)
        ]);
        $insurance = factory(App\Models\Policy\SInsuranceCarrier::class)->create([
            'insurance_carrier' => 'Solidaria',
            'identification' => rand(81600000, 90000000)
        ]);
        $insurance = factory(App\Models\Policy\SInsuranceCarrier::class)->create([
            'insurance_carrier' => 'Mundial',
            'identification' => rand(81600000, 90000000)
        ]);
        $insurance = factory(App\Models\Policy\SInsuranceCarrier::class)->create([
            'insurance_carrier' => 'Liberty',
            'identification' => rand(81600000, 90000000)
        ]);
    }
}
