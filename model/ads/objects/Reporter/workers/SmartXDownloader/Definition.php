<?php
namespace model\ads\Reporter\workers\SmartXDownloader;

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
            'DEFAULT'     => 'smartx_downloader'
        ],
        'args' => [
            'TYPE'   => 'Container',
            'FIELDS' => [
                'task' => [
                    'TYPE'      => 'String',
                    'MINLENGTH' => 2,
                    'MAXLENGTH' => 64,
                    'LABEL'     => 'Tarea',
                    'DEFAULT'   => 'model\\ads\\Reporter\\workers\\SmartXDownloader',
                ],
                'type' => [
                    'TYPE'    => 'String',
                    'DEFAULT' => 'List',
                ],
                'params' => [
                    'LABEL'   => 'Parámetros',
                    'TYPE'    => 'Container',
                    'FIELDS'  => [
                        'items' => [
                            'LABEL'   => 'Llamadas',
                            'TYPE'    => 'Array',
                            'DEFAULT' => [],
                            "ELEMENTS" => [
                                "TYPE" => "Container",
                                "FIELDS" => [
                                    'call' => [
                                        'TYPE' => 'String',
                                        'MINLENGTH' => 2,
                                        'MAXLENGTH' => 256,
                                        'LABEL'     => 'Llamada',
                                    ],
                                    'params' => [
                                        //'TYPE' => 'Dictionary',
                                        //'VALUETYPE' => [
                                            'TYPE'   => 'Container',
                                            'FIELDS' => [
                                                "changed_within" => [
                                                    'TYPE'    => 'Integer',
                                                    'MIN'     => 0,
                                                    'LABEL'   => 'Segundos desde actualización',
                                                    'DEFAULT' => null, 
                                                ],
                                                "marketplace_id" => [
                                                    'TYPE'    => 'Integer',
                                                    'LABEL'   => 'Marketplace ID',
                                                    'DEFAULT' => null,
                                                ],
                                                "filter" => [
                                                    'TYPE'    => 'String',
                                                    'LABEL'   => 'Filtros manuales',
                                                    'DEFAULT' => null,
                                                ],
                                                "name" => [
                                                    'TYPE'    => 'String',
                                                    'LABEL'   => 'Nombre',
                                                    'DEFAULT' => null,
                                                ],
                                                "sort_by" => [
                                                    'TYPE' => 'String',
                                                    'LABEL'   => 'Ordenar por',
                                                    'DEFAULT' => null,
                                                ],
                                            ],
                                        //],
                                    ],
                                ],
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
