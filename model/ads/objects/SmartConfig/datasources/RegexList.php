<?php
namespace model\ads\SmartConfig\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/datasources/RegexList.php
 CLASS:Definition
 *
 *
 **/

class RegexList
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'domain' => [
//                 'TYPE' => 'model\\ads\\SmartConfig\\types\\Domain',
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
                'REQUIRED'=>'true',
            ],
        ],
        'FIELDS' => [
            'domain' => [
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'domain',
            ],
            'regex' => [
                'MODEL' => '\model\ads\SmartConfig',
                'FIELD' => 'regex',
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


