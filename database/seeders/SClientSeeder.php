<?php

use Illuminate\Database\Seeder;

class SClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = factory(App\Models\SClient::class, 20)->create();
    }
}
