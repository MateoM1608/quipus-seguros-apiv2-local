<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = App\Models\Module::all();
        $users = App\Models\User::where('connection', Config::get('database.default'))->get();

        foreach ($users as $user) {
            foreach ($modules as $module) {
                $permission_id = \App\Models\Permission::where('user_id', $user->id)
                    ->where('module_id', $module->id)->first();

                if (!$permission_id) {
                    $permission = factory(\App\Models\Permission::class)->create([
                        'user_id' => $user->id,
                        'module_id' => $module->id
                    ]);
                }
            }
        }
    }
}
