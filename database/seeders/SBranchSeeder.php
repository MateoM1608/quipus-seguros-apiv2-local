<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Vida',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Autos',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Incendios',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Salud',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Vivienda',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Hogar',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
        $branch = factory(App\Models\Policy\SBranch::class)->create([
            'name' => 'Educación',
            'commission' => rand(0.00, 1.00),
            'tax' => rand(0.00, 1.00),
            's_insurance_carrier_id' => rand(1, 5)
        ]);
    }
}
