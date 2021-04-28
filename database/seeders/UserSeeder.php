<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->create([
            'name' => 'Soporte',
            'email' => 'suport@amauttasystems.com'
        ]);
    }
}
