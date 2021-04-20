<?php

use Illuminate\Database\Seeder;

class SPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $policy = factory(App\Models\Policy\SPolicy::class, 20)->create();
    }
}
