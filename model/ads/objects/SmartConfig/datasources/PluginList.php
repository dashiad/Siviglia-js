<?php
namespace model\ads\SmartConfig\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/datasources/PluginList.php
 CLASS:Definition
 *
 *
 **/

class PluginList
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'domain' => [
//                 'TYPE' => 'model/ads/SmartConfig/types/Domain',
                'TYPE' => 'String',
                'LABEL' => 'Domain',
                'REQUIRED'=> true,
            ],
            'regex' => [
//                 'TYPE' => 'model/ads/SmartConfig/types/Regex',
                'TYPE' => 'String',
                'LABEL' => 'Regex',
                'REQUIRED' => true,
                'DEFAULT' => '.*'
            ],
        ],
        'FIELDS' => [
            'domain' => [
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'domain',
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
                            'domain' => '[%domain]',
                            'regex' => '[%regex]'
                        ],
                        'CONDITIONS' => [
                            [
                                'FILTER' => '[%domain%]',
                                'TRIGGER_VAR'=> 'domain',
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


