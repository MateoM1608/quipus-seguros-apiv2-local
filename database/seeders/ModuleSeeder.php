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
                'name' => 'Sidebar',
                'children' => [
                    [
                        'name' => 'login',
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
                        'description' => 'administration',
                        'name' => 'Administración',
                        'icon' => 'fas fa-users-cog',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'children' => [
                            [
                                'description' => 'module',
                                'name' => 'Modulos',
                                'url' => '/module',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'user',
                                'name' => 'Usuarios',
                                'url' => '/user',
                                'icon' => 'fas fa-user-friends',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => '',
                                'show' => 1,
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
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 1,
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
                            ],
                            [
                                'description' => 'agency',
                                'name' => 'Agencias',
                                'url' => '/agency',
                                'icon' => 'fas fa-house-user',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'branch',
                                'name' => 'Ramos aseguradoras',
                                'url' => '/branch',
                                'icon' => 'fas fa-code-branch',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'insuranceCarrier',
                                'name' => 'Aseguradoras',
                                'url' => '/insuranceCarrier',
                                'icon' => 'fas fa-building',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'stagesCrm',
                                'name' => 'Estádos CRM',
                                'url' => '/stagesCrm',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 1,
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
                    ],
                    [
                        'description' => 'policies',
                        'name' => 'Polizas',
                        'url' => '/policies',
                        'icon' => 'fas fa-umbrella',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                        'show' => 1,
                        'class' => 'sidebar-nav-mini-hide',
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
                                'name' => 'risk',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'name' => 'claim',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'name' => 'annex',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'name' => 'payment',
                                'icon' => 'icon-speedometer',
                                'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                                'class' => 'sidebar-nav-mini-hide',
                                'show' => 0,
                            ],
                            [
                                'name' => 'commission',
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
                        'children' => [
                            [
                                'description' => 'expiration',
                                'name' => 'Vencimientos',
                                'url' => '/reports/expiration',
                                'icon' => 'fas fa-chart-bar',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'portfolio',
                                'name' => 'Cartera',
                                'url' => '/reports/portfolio',
                                'icon' => 'fas fa-hand-holding-usd',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'commissionReceivable',
                                'name' => 'Comisiones por cobrar',
                                'url' => '/reports/commissionReceivable',
                                'icon' => 'fas fa-file-invoice-dollar',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'production',
                                'name' => 'Producción',
                                'url' => '/reports/production',
                                'icon' => 'fas fa-file-invoice-dollar',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'dashboard',
                                'name' => 'Dashboard',
                                'url' => '/reports/dashboard',
                                'icon' => 'fas fa-hand-holding-usd',
                                'class' => '',
                                'show' => 0,
                            ],
                        ]
                    ],
                    [
                        'description' => 'identificationType',
                        'name' => 'identificationType',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'country',
                        'name' => 'country',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'city',
                        'name' => 'city',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'processStage',
                        'name' => 'processStage',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'name' => 'process',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'operationType',
                        'name' => 'operationType',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'name' => 'operation',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'operationComment',
                        'name' => 'operationComment',
                        'icon' => 'icon-speedometer',
                        'badge' => json_encode(['variant' => 'primary', 'text' => 'NEW']),
                    ],
                    [
                        'description' => 'crm',
                        'name' => 'Crm',
                        'icon' => 'fas fa-users-cog',
                        'class' => 'sidebar-nav-mini-hide',
                        'show' => 1,
                        'children' => [
                            [
                                'description' => 'cases',
                                'name' => 'Servicio al cliente',
                                'url' => '/crm/cases',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 1,
                            ],
                            [
                                'description' => 'oportunity',
                                'name' => 'Oportunidades',
                                'url' => '/crm/cases',
                                'icon' => 'fas fa-th-large',
                                'class' => '',
                                'show' => 1,
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
