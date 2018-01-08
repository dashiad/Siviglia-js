<?php

namespace backoffice\config;

class Config
{
    public $definition = [
    ];

    // La definicion de permisos consiste en 3 arrays.Todos los elementos del primer nivel del array, deben ser claves de arrays.
    public $userManagement = [

        'REQUIRE_UNIQUE_EMAIL' => true,
        'PASSWORD_ENCODING' => 'PLAINTEXT', // PLAINTEXT, MD5
        'ATTEMPTS_BEFORE_LOCKOUT' => 0,
        'REQUIRE_ACCOUNT_VALIDATION' => false,
        'LOGIN_ON_CREATE' => true,
        'NOT_ALLOWED_NICKS' => ['*admin*'],
    ];

    public $permissions = [
        // Definicion de los tipos de usuarios que acceden al sistema.
        // It's important to understand the criteria for nesting groups.
        // It's a b
        'Users' => [
            'AllUsers' => [
                'Anonymous' => [],
                'Logged' => [
                    'WebStaff' => [
                        'Admins' => [
                            'WebAdmin' => [],
                            'AppAdmin' => [],
                        ],
                        'Editors' => [],
                        'FullAdmin' => [],
                    ],
                ],
            ],
        ],
        // Definicion de permisos que pueden ser requeridos para acceder a los elementos del sistema.
        'Permissions' => [
            'AllPerms' => [
                'Access' => ['accessLevelOne', 'accessLevelTwo', 'accessLevelThree'],
                'Sys' => [
                    'user' => ['create', 'edit', 'delete', 'view', 'list'],
                    'admin' => ['adminCreate', 'adminDelete', 'adminView', 'adminList', 'adminEdit'],
                    'modules' => [
                        'backofficeModules' => ['adminWebModule'],

                    ],
                ],
            ],
        ],
        // Definicion de los elementos del sistema, que pueden requerir permisos para acceder a ellos,.
        // Normalmente, estos elementos van a ser los modulos, pero aqui se ponen elementos globales,
        'Objects' => [
            'AllObjects' => [
                'Access' => ['AdminWebSite'],
                'Sys' => [
                    'modules' => [
                        'backofficeModules' => [],

                    ],
                ],
            ],
        ],
        // Permisos por defecto.Los permisos se especifican como tres arrays.Cada uno de estos arrays contiene
        // un elemento ITEM o un elemento GROUP segun si se aplica a un item, o a un grupo.
        // (Los items son las hojas de los arrays Users,Permissions y Objects).
        // Los elementos ITEM o GROUP especifican el grupo raiz (de primer nivel), donde se encuentra el ITEM o GROUP que se busca.
        // El orden de especificacion de los 3 arrays, es:Permiso / Usuario / Objeto.
        // El campo 'objeto' es opcional.
        // Si existe un cuarto elemento, con valor '0', la accion es 'denegar el permiso'
        'DefaultPermissions' => [
            [
                ['GROUPS' => ['AllPerms']],
                ['GROUPS' => ['AllUsers']],
                ['GROUPS' => ['AllObjects']],
            ],
            // Todos los usuarios que pertenecen a WebStaff, tienen acceso nivel uno a la administracion.
            [
                ['ITEMS' => ['accessLevelOne']],
                ['GROUPS' => ['WebStaff']],
                ['ITEMS' => 'AdminWebSite'],
            ],
            // Todos los usuarios Logged tienen permisos de tipo 'user' sobre Sys
            [
                ['GROUPS' => ['user']],
                ['GROUPS' => ['Logged']],
                ['GROUPS' => ['Sys']],
            ],
            // Los usuarios webadmin tienen permisos admin y webmodules sobre webmodules.
            [
                ['GROUPS' => ['webModules', 'admin', 'user']],
                ['GROUPS' => ['WebAdmin']],
                ['GROUPS' => ['backofficeModules']],
            ],
            // Los usuarios FullAdmin tienen permisos admin y modules sobre admin
            [
                ['GROUPS' => ['admin', 'modules', 'user']],
                ['GROUPS' => ['FullAdmin']],
                ['GROUPS' => ['modules']],
            ],
        ],
    ];
}
