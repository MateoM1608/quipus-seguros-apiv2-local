<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Config;

use App\Models\Permission;
use App\Models\Module;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = Module::all();
        $users = User::where('connection', Config::get('database.default'))->get();

        foreach ($users as $user) {
            foreach ($modules as $module) {
                $permission_id = Permission::where('user_id', $user->id)
                    ->where('module_id', $module->id)->first();

                if (!$permission_id) {
                    $permission = Permission::factory()->create([
                        'user_id' => $user->id,
                        'module_id' => $module->id
                    ]);
                }
            }
        }
    }
}
