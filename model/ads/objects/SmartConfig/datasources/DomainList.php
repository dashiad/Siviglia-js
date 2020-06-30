<?php
namespace model\ads\SmartConfig\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/datasources/DomainList.php
 CLASS:Definition
 *
 *
 **/

class DomainList
{
    static $definition = [
        'ROLE' => 'list', 
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            //
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
                            'action' => 'getFolderContent',
                        ],
                        'CONDITIONS' => [
                            //
                        ],
                    ],
                ],
            ],
        ],
    ];
}


