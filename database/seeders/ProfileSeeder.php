<?php

use Illuminate\Database\Seeder;

// Models
use App\Models\Module;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = Module::all();

        $profile = \App\Models\Profile::firstOrCreate([
            'description' => 'Administrador'
        ]);

        $modulesAdmin = $modules->where('description', '<>', 'module');

        $this->addPermission($profile->id, $modulesAdmin);
    }

    public function addPermission($profile, $modules)
    {
        foreach ($modules as $module) {
            \App\Models\PermissionProfile::firstOrCreate([
                'module_id' => $module->id,
                'profile_id' => $profile,
                'actions' => '{"see": true, "edit": true, "create": true, "delete": true}'
            ]);
        }
    }
}
