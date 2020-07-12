<?php
namespace model\ads\SmartConfig\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/datasources/SingleConfig.php
 CLASS:Definition
 *
 *
 **/

class SingleConfig
{
    static $definition = [
        'ROLE' => 'list', // posibles roles?
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'domain' => [
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
                'REQUIRED'=>'true',
            ],
            'regex' => [
                'TYPE' => 'Array',
                'LABEL' => 'Regex',
                'REQUIRED' => 'false',
                'ELEMENTS' => [
                    'TYPE' => 'String',
                ],
            ],
            'plugin' => [
                'TYPE' => 'Array', 
                'LABEL' => 'Plugin',
                'REQUIRED' => 'false',
                'ELEMENTS' => [
                    'TYPE' => 'String',
                ],
            ],
        ],
        'FIELDS' => [
            // TODO: revisar formato de cada campo
            'domain' => [
                'LABEL' => 'Domain',
                'TYPE' => 'String',
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'domain',
            ],
            'config' => [
                'LABEL' => 'Config',
//                 'TYPE'  => 'model/ads/SmartConfig/types/SmartConfig',
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'config',
            ],
        ],
        'PERMISSIONS' => [],
        'SOURCE'      => [
            'STORAGE' => [
                'smartconfig' => [
                    'NAME'   => 'model\\ads\\SmartConfig',
                    'CLASS'  => 'model\\ads\\SmartConfig\\serializers\\SmartConfigSerializer',
                    'DEFINITION' => [
                        'BASE'   => [
                            'action' => 'getFileContent',
                            'domain' => "[%domain%]",
                            'regex'  => "[%regex%]",
                            'plugin' => "[%plugin%]",
                        ],
                        'CONDITIONS' => [
                            [
                                'FILTER' => '[%domain%]',
                                'TRIGGER_VAR'=> 'domain',
                            ],
                            [
                                'FILTER' => '[%plugin%]',
                                'TRIGGER_VAR'=> 'plugin',
                            ],
                            [
                                'FILTER' => '[%regex%]',
                                'TRIGGER_VAR'=> 'regex',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
}


