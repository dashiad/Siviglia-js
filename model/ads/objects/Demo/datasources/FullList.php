<?php
namespace model\ads\Demo\datasources;
/**
 FILENAME:/var/www/adtopy/model/ads/objects/Demo/datasources/FullList.php
 CLASS:Definition
 *
 *
 **/

class FullList
{
    static $definition = [
        'ROLE' => 'list', 
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            //
        ],
        'FIELDS' => [
            'id' => [
                'MODEL' => '\model\ads\Demo',
                'FIELD' => 'id',
            ],
            'domain' => [
                'MODEL' => '\model\ads\Demo',
                'FIELD' => 'domain',
            ],
        ],
        'PERMISSIONS' => [],
        'SOURCE'      => [
            'STORAGE' => [
                'MYSQL'=> [
                    'DEFINITION' => [
                        'TABLE' => 'demo',
                        'BASE' => [
                            'id',
                            'domain',
                        ],
                        'CONDITIONS' => [
                        ]
                    ]
                ]
            ],
        ],
    ];
}


