<?php
namespace model\ads\SmartConfig;

/**
 * FILENAME:/var/www/adtopy/model/ads/objects/SmartConfig/Definition.php
 * CLASS:Definition
 */
use lib\model\BaseModelDefinition;

class Definition extends BaseModelDefinition
{

    static $definition = [
        'ROLE' => 'ENTITY',
        'DEFAULT_SERIALIZER' => 'smartconfig',
        'DEFAULT_WRITE_SERIALIZER' => 'smartconfig',
        'FIELDS' => [
            'configType' => [
                'TYPE' => 'String',
                'LABEL' => 'Tipo de configuración',
                'REQUIRED' => true,
                'DEFAULT' => 'default',
            ],
            'domain' => [
                'TYPE' => 'String',
                'LABEL' => 'Dominio',
                'REQUIRED' => true,
                'DEFAULT' => null,
            ],
            'config' => [
                'TYPE' => 'Array',
                'LABEL' => 'Regex',
                'ELEMENTS' => [
                    'LABEL' => 'Plugin',
                    'TYPE' => 'Container',
                    'KEEP_KEY_ON_EMPTY' => false,
                    'FIELDS' => [
                        'actions' => [
                            'TYPE' => 'Array',
                            'LABEL' => 'Acciones',
                            'ELEMENTS' => [
                                'TYPE' => 'TypeSwitcher',
                                "ON"=>[
                                    ["IS"=>"String", "THEN"=>"simple"],
                                    ["IS"=>"Object", "THEN"=>"composite"],
                                ],                                
                                'ALLOWED_TYPES' => [
                                    'simple' => [
                                        'TYPE' => 'String',
                                    ],
                                    'composite' => [
                                        'TYPE' => 'Dictionary',
                                        "VALUETYPE" => [
                                            'TYPE' => "String",
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'Exelate' => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\Exelate',
                            'LABEL' => 'Plugin Exelate',
                        ],
                        'Adobe' => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\Adobe',
                            'LABEL' => 'Plugin Adobe',
                        ],
                        "BlueKaiPlugin" => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\BlueKai',
                            'LABEL' => 'Plugin BlueKai',
                        ],
                        'ImageLoaderUrl' => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\ImageLoaderUrl',
                            'LABEL' => 'Plugin ImageLoaderUrl',
                        ],
                        'AdnSegments' => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\AdnSegments',
                            'LABEL' => 'Plugin AdnSegments',
                        ],
                        'GPTConfig' => [
                            'TYPE' => '\\model\\ads\\SmartConfig\\types\\GPTConfig',
                            'LABEL' => 'Plugin GPTConfig',
                        ],
                    ],
                ],  
            ],
        ],
        'INDEXFIELDS' => [
            "domain",
        ]
    ];
}
