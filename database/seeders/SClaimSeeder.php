<?php

use Illuminate\Database\Seeder;

class SClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $policy = factory(App\Models\Policy\SClaim::class, 20)->create();
    }
}
