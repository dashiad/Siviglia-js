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
                'TYPE' => '/model/ads/SmartConfig/types/Domain', 
                'LABEL' => 'Mensaje',
                'REQUIRED'=>'true',
            ],
            'regex' => [
                'TYPE' => '/model/ads/SmartConfig/types/Regex', 
                'LABEL' => 'Regex',
                'REQUIRED' => 'true',
                'DEFAULT' => '.*'
            ],
            'plugin' => [
                'TYPE' => 'String', 
                'LABEL' => 'Plugin',
                'REQUIRED' => 'true',
            ],
        ],
        'FIELDS' => [
            // TODO: revisar formato de cada campo
            'domain' => [
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'domain',
            ],
            'plugin' => [
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'plugin',
            ],
            'config' => [
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


