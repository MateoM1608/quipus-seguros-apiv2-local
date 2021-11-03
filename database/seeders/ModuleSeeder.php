<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

//Models
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'description' => 'sidebar',
                'name' => 'App',
                'children' => [
                    [
                        'description' => 'login',
                        'name' => 'Inicio sesión',
                        'url' => '/login',
                        'icon' => 'icon-speedometer',
                        'class' => 'sidebar-nav-mini-hide',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 0,
                        'children' => [
                            [
                                'name' => 'refrescar',
                                'class' => 'sidebar-nav-mini-hide',
                                'url' => '/login/refresh',
                                'icon' => 'fa fa-th',
                                'show' => 0,
                            ],
                            [
                                'name' => 'logout',
                                'class' => 'sidebar-nav-mini-hide',
                                'url' => '/login/logout',
                                'icon' => 'fa fa-th',
                                'show' => 0,
                            ],
                            [
                                'name' => 'me',
                                'class' => 'sidebar-nav-mini-hide',
                                'url' => '/login/me',
                                'icon' => 'fa fa-th',
                                'show' => 0,
                            ],
                        ],

                    ],
                    [
                        'description' => 'herramientas',
                        'name' => 'Herramientas',
                        'icon' => 'fas fa-sliders-h',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'order' => 0,
                        'children' => [
                            [
                                'description' => 'upload',
                                'name' => 'Cargues',
                                'url' => '/upload',
                                'icon' => 'fas fa-cloud-upload-alt',
                                'class' => '',
                                'show' => 1,
                                'order' => 0,
                            ],
                        ]
                    ],
                    [
                        'description' => 'administration',
                        'name' => 'Administración',
                        'icon' => 'fas fa-users-cog',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'order' => 1,
                        'children' => [
                            [
                                'description' => 'module',
                                'name' => 'Modulos',
                                'url' => '/module',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 0,
                                'order' => 9,
                            ],
							[
                                'description' => 'user',
                                'name' => 'Usuarios',
                                'url' => '/user',
                                'icon' => 'fas fa-user-friends',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => '',
                                'show' => 1,
                                'order' => 7,
							],
                            [
                                'description' => 'user_children',
                                'name' => 'Usuarios',
                                'url' => '/user',
                                'icon' => 'fas fa-user-friends',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => '',
                                'show' => 0,
                                'order' => 0,
                                'children' => [
                                    [
                                        'description' => 'permission',
                                        'name' => 'Permisos',
                                        'url' => '/user/permission',
                                        'icon' => 'fa fa-chain-broken',
                                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                        'class' => '',
                                        'show' => 1,
                                    ],
                                    [
                                        'description' => 'password',
                                        'name' => 'Cambiar contraseña',
                                        'url' => '/user/password/change',
                                        'icon' => 'fa fa-chain-broken',
                                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                        'class' => '',
                                        'show' => 1,
                                    ],
                                    [
                                        'description' => 'profileuser',
                                        'name' => 'Perfiles de usuario',
                                        'url' => '/profileuser',
                                        'icon' => 'fa fa-chain-broken',
                                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                        'class' => '',
                                        'show' => 1,
                                    ]
                                ]
                            ],
                            [
                                'description' => 'profile',
                                'name' => 'Perfiles',
                                'url' => '/profile',
                                'icon' => 'far fa-address-card',
                                'class' => '',
                                'show' => 1,
                                'order' => 8,
                            ],
                            [
                                'description' => 'profile_children',
                                'name' => 'Perfiles',
                                'url' => '',
                                'icon' => '',
                                'class' => '',
                                'show' => 0,
                                'order' => 0,
                                'children' => [
                                    [
                                        'description' => 'permission-profile',
                                        'name' => 'Permisos de perfil',
                                        'url' => '/profile/permission-profile',
                                        'icon' => 'fa fa-chain-broken',
                                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                        'class' => '',
                                        'show' => 1,
                                    ],
                                ]
                            ],
                            [
                                'description' => 'vendor',
                                'name' => 'Vendedores',
                                'url' => '/vendor',
                                'icon' => 'fas fa-briefcase',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => '',
                                'show' => 1,
                                'order' => 4,
                            ],
                            [
                                'description' => 'agency',
                                'name' => 'Agencias',
                                'url' => '/agency',
                                'icon' => 'fas fa-house-user',
                                'class' => '',
                                'show' => 1,
                                'order' => 3,
                            ],
                            [
                                'description' => 'branch',
                                'name' => 'Ramos',
                                'url' => '/branch',
                                'icon' => 'fas fa-code-branch',
                                'class' => '',
                                'show' => 1,
                                'order' => 2,
                            ],
                            [
                                'description' => 'insuranceCarrier',
                                'name' => 'Aseguradoras',
                                'url' => '/insuranceCarrier',
                                'icon' => 'fas fa-building',
                                'class' => '',
                                'show' => 1,
                                'order' => 1,
                            ],
                            [
                                'description' => 'stagesCrm',
                                'name' => 'Etapas CRM',
                                'url' => '/stagesCrm',
                                'icon' => 'fas fa-sort-amount-up',
                                'class' => '',
                                'show' => 1,
                                'order' => 5,

                            ],
                            [
                                'description' => 'areasCrm',
                                'name' => 'Areas CRM',
                                'url' => '/areasCrm',
                                'icon' => 'fas fa-boxes',
                                'class' => '',
                                'show' => 1,
                                'order' => 6,
                            ]
                        ]
                    ],
                    [
                        'description' => 'client',
                        'name' => 'Clientes',
                        'url' => '/client',
                        'icon' => 'fas fa-users',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'order' => 2,
                    ],
                    [
                        'description' => 'policies',
                        'name' => 'Polizas',
                        'url' => '/policies',
                        'icon' => 'fas fa-umbrella',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 1,
                        'class' => 'sidebar-nav-mini-hide',
                        'order' => 3,
                    ],
                    [
                        'description' => 'policies_children',
                        'name' => 'Polizas',
                        'url' => '/policies',
                        'icon' => 'fas fa-umbrella',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 0,
                        'class' => 'sidebar-nav-mini-hide',
                        'children' => [
                            [
                                'description' => 'policies_id',
                                'name' => 'Id Polizas',
                                'url' => '/policies/:id',
                                'icon' => 'fas fa-umbrella',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => '',
                                'show' => 0,
                            ],
                            [
                                'description' => 'risk',
                                'name' => 'Riesgos',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'description' => 'claim',
                                'name' => 'Siniestros',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'description' => 'annex',
                                'name' => 'Anexos',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'description' => 'payment',
                                'name' => 'Pagos',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'description' => 'commission',
                                'name' => 'Comisiones',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                        ]
                    ],
                    [
                        'description' => 'reports',
                        'name' => 'Reportes',
                        'icon' => 'fas fa-chart-pie',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'order' => 5,
                        'children' => [
                            [
                                'description' => 'expiration',
                                'name' => 'Vencimientos',
                                'url' => '/reports/expiration',
                                'icon' => 'fas fa-chart-bar',
                                'class' => '',
                                'show' => 1,
                                'order' => 1,
                            ],
                            [
                                'description' => 'portfolio',
                                'name' => 'Cartera',
                                'url' => '/reports/portfolio',
                                'icon' => 'fas fa-hand-holding-usd',
                                'class' => '',
                                'show' => 1,
                                'order' => 5,
                            ],
                            [
                                'description' => 'commissionReceivable',
                                'name' => 'Comisiones por cobrar',
                                'url' => '/reports/commissionReceivable',
                                'icon' => 'fas fa-file-invoice-dollar',
                                'class' => '',
                                'show' => 1,
                                'order' => 2,
                            ],
                            [
                                'description' => 'production',
                                'name' => 'Producción',
                                'url' => '/reports/production',
                                'icon' => 'fas fa-flag-checkered',
                                'class' => '',
                                'show' => 1,
                                'order' => 4,
                            ],
                            [
                                'description' => 'indicators',
                                'name' => 'Producción',
                                'url' => '/reports/indicators/production',
                                'icon' => 'fas fa-flag-checkered',
                                'class' => '',
                                'show' => 0,
                                'order' => 0,
                            ],
                            [
                                'description' => 'dashboard',
                                'name' => 'Dashboard',
                                'url' => '/reports/dashboard',
                                'icon' => 'fas fa-hand-holding-usd',
                                'class' => '',
                                'show' => 0,
                            ],
                            [
                                'description' => 'vendorCommission',
                                'name' => 'Comisiones por pagar',
                                'url' => '/reports/vendorCommission',
                                'icon' => 'fas fa-money-check-alt',
                                'class' => '',
                                'show' => 1,
                                'order' => 3,
                            ],
                            [
                                'description' => 'taskCrm',
                                'name' => 'Tareas CRM',
                                'url' => '/reports/taskCrm',
                                'icon' => 'fas fa-money-check-alt',
                                'class' => '',
                                'show' => 0,
                                'order' => 0,
                            ],
                        ]
                    ],
                    [
                        'description' => 'identificationType',
                        'name' => 'Tipo Identificacíón',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 0,
                    ],
                    [
                        'description' => 'country',
                        'name' => 'Paises',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 0,
                    ],
                    [
                        'description' => 'city',
                        'name' => 'Ciudades',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 0,
                    ],
                    [
                        'description' => 'crm',
                        'name' => 'CRM',
                        'url' => '/crm/cases',
                        'icon' => 'fas fa-users-cog',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'order' => 4,
                        'class' => ''

                    ],
                    [
                        'description' => 'crm_children',
                        'name' => 'CRM',
                        'url' => '/crm/cases',
                        'icon' => 'fas fa-users-cog',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 0,
                        'children' => [

                            [
                                'description' => 'typeCases',
                                'name' => 'Tipos de Casos',
                                'url' => '/crm/typeCases',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 0,
                            ],
                            [
                                'description' => 'cases',
                                'name' => 'Casos',
                                'url' => '/crm/cases/:id',
                                'icon' => 'fas fa-users-cog',
                                'class' => '',
                                'show' => 0,
                            ],
                            [
                                'description' => 'notes',
                                'name' => 'Notas',
                                'url' => '/crm/notes',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 0,
                            ]
                        ]
                    ],
                ]
            ],
        ];

        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    public function addModule($module, $parent = null)
    {
        $data  = $module;
        $data['parent'] = $parent;
        $data['description'] = isset($data['description']) ? $data['description'] : Str::snake($data['name']);
        unset($data['children']);

        $_module = Module::withTrashed()->firstOrCreate(['description' => $data['description']], $data);

        if (array_key_exists('children', $module)) {
            foreach ($module['children'] as $_module_) {
                $this->addModule($_module_, $_module->id);
            }
        }
    }
}
