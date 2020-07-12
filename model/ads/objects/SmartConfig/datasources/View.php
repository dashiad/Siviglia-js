<?php
namespace model\ads\SmartConfig\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/datasources/View.php
 CLASS:Definition
 *
 *
 **/

class View
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'domain' => [
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
                'REQUIRED'=>'true',
            ],
        ],
        'FIELDS' => [
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
                            'action' => 'getFileContent',
                            'domain' => "[%domain%]",
                        ],
                        'CONDITIONS' => [
                            [
                                'FILTER' => '[%domain%]',
                                'TRIGGER_VAR'=> 'domain',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
}


