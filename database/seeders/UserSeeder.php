<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\Models\User::class)->create([
            'name' => 'Administrator',
            'email' => 'administrator@seguros.com.co'
        ]);

        $users = factory(App\Models\User::class, 20)->create();
    }
}
