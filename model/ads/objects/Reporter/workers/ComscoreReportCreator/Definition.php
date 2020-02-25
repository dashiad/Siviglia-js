<?php
namespace model\ads\Reporter\workers\ComscoreReportCreator;

use model\web\Jobs\BaseWorkerDefinition;

class Definition extends BaseWorkerDefinition
{
    /**
     * 
     * @property String type
     * @property String name
     * @property Container args
     * 
     */
       
    static $definition = [
        'type'   => [
            'TYPE'   => 'String',
            'LABEL'  => 'Tipo',
            'DEFAULT' => 'task',
        ],
        'name' => [
            'TYPE'        => 'String',
            'LABEL'       => 'Nombre',
            'DEFAULT'     => 'comscore_report_creator'
        ],
        'args' => [
            'TYPE'   => 'Container',
            'FIELDS' => [
                'task' => [
                    'TYPE'      => 'String',
                    'MINLENGTH' => 2,
                    'MAXLENGTH' => 64,
                    'LABEL'     => 'Tarea',
                    'DEFAULT'   => 'model\\ads\\Reporter\\workers\\ComscoreReportCreator',
                ],
                'type' => [
                    'TYPE'    => 'String',
                    'DEFAULT' => 'DateRange',
                ],
                'params' => [
                    'LABEL'   => 'Parámetros',
                    'TYPE'    => 'Container',
                    'FIELDS'  => [
                        'region' => [
                            'TYPE' => 'String',
                            'DEFAULT' => 'spain',
                        ],
                        'start_date' => [
                            'TYPE' => 'Date',
                        ],
                        'end_date' => [
                            'TYPE' => 'Date',
                        ],
                        'campaigns' => [
                            'LABEL'   => 'Campañas',
                            'TYPE'    => 'Array',
                            'DEFAULT' => [],
                            "ELEMENTS" => [
                                "TYPE" => "String",
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
