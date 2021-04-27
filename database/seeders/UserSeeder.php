<?php

namespace Database\Seeders;

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
            'name' => 'Soporte',
            'email' => 'suport@amauttasystems.com'
        ]);
    }
}
