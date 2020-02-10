<?php
namespace model\ads\Reporter\workers\DfpReport;

use model\web\Jobs\BaseWorkerDefinition;

class Definition extends BaseWorkerDefinition
{
    static $definition = [
        'type'   => [
            'TYPE'   => 'String',
            'LABEL'  => 'Tipo',
            'DEFAULT' => 'task',
        ],
        'name' => [
            'TYPE'        => 'String',
            'LABEL'       => 'Nombre',
            'DEFAULT'     => 'dfp_report'
        ],
        'args' => [
            'TYPE'   => 'Container',
            'FIELDS' => [
                'task' => [
                    'TYPE'      => 'String',
                    'MINLENGTH' => 2,
                    'MAXLENGTH' => 64,
                    'LABEL'     => 'Tarea',
                    'DEFAULT'   => 'DfpReport',
                ],
                'type' => [
                    'TYPE'    => 'String',
                    'DEFAULT' => 'List',
                ],
                'params' => [
                    'LABEL'   => 'Parámetros',
                    'EXTENDS' => 'Container', // ???
                    'TYPE'    => 'Container',
                    'FIELDS'  => [
                        'items' => [
                            'LABEL'   => 'Elementos',
                            'TYPE'    => 'Array',
                            'DEFAULT' => [],
                            "ELEMENTS" => [
                                "TYPE" => "String"
                            ]
                        ],
                        'max_chunk_size' => [
                            'LABEL'   => 'Tamaño máximo del bloque',
                            'TYPE'    => 'Integer',
                            'MIN'     => 1,
                            'MAX'     => 65535,
                            'DEFAULT' => 1,
                        ],
                    ],
                ],
            ], 
        ],
    ];
}
