<?php

use Illuminate\Database\Seeder;

class SAnnexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = factory(App\Models\Policy\SAnnex::class, 20)->create();
    }
}
