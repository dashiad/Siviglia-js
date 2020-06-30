<?php
namespace model\ads\Demo;

/**
 * FILENAME:/var/www/adtopy/model/ads/objects/Demo/Definition.php
 * CLASS:Definition
 */
use lib\model\BaseModelDefinition;

class Definition extends BaseModelDefinition
{

    static $definition = [
        'ROLE' => 'ENTITY',
        'TABLE' => 'demo',
        'FIELDS' => [
            'id' => [
                'TYPE' => 'AutoIncrement',
                'LABEL' => 'Id',
                'REQUIRED' => true,
            ],
            'domain' => [
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
                'REQUIRED' => true,
            ],
            'config' => [
                'TYPE' => 'String',
                'LABEL' => 'config',
                'REQUIRED' => false,
            ],
        ],
        'INDEXFIELDS' => [
            "id",
        ]
    ];
}
