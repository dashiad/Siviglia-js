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
                'TYPE' => 'Container',
                'LABEL' => 'micampo container',
                'FIELDS' => [
                    '.*' => [
                        'LABEL' => 'Plugin',
                        'TYPE' => 'Container',
//                         'KEEP_KEY_ON_EMPTY' => false,
                        'FIELDS' => [
                            'Exelate' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\Exelate',
                                'LABEL' => 'Plugin Exelate',
                            ],
//                             'Adobe' => [
//                                 'TYPE' => '\\model\\ads\\SmartConfig\\types\\Adobe',
//                                 'LABEL' => 'Plugin Adobe',
//                             ],
//                             "BlueKaiPlugin" => [
//                                 'TYPE' => '\\model\\ads\\SmartConfig\\types\\BlueKai',
//                                 'LABEL' => 'Plugin BlueKai',
//                             ],
//                             'ImageLoaderUrl' => [
//                                 'TYPE' => '\\model\\ads\\SmartConfig\\types\\ImageLoaderUrl',
//                                 'LABEL' => 'Plugin ImageLoaderUrl',
//                             ],
                            'AdnSegments' => [
                                'TYPE' => '\\model\\ads\\SmartConfig\\types\\AdnSegments',
                                'LABEL' => 'Plugin AdnSegments',
                            ],
//                             'GPTConfig' => [
//                                 'TYPE' => '\\model\\ads\\SmartConfig\\types\\GPTConfig',
//                                 'LABEL' => 'Plugin GPTConfig',
//                             ],
                        ],
                    ],
                ],
            ],
//             'config' => [
//                 'TYPE' => 'Dictionary',
//                 'LABEL' => 'Regex',
//                 'VALUETYPE' => [
//                     'LABEL' => 'Plugin',
//                     'TYPE' => 'Container',
//                     'KEEP_KEY_ON_EMPTY' => false,
//                     'FIELDS' => [
//                         'Exelate' => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\Exelate',
//                             'LABEL' => 'Plugin Exelate',
//                         ],
//                         'Adobe' => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\Adobe',
//                             'LABEL' => 'Plugin Adobe',
//                         ],
//                         "BlueKaiPlugin" => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\BlueKai',
//                             'LABEL' => 'Plugin BlueKai',
//                         ],
//                         'ImageLoaderUrl' => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\ImageLoaderUrl',
//                             'LABEL' => 'Plugin ImageLoaderUrl',
//                         ],
//                         'AdnSegments' => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\AdnSegments',
//                             'LABEL' => 'Plugin AdnSegments',
//                         ],
//                         'GPTConfig' => [
//                             'TYPE' => '\\model\\ads\\SmartConfig\\types\\GPTConfig',
//                             'LABEL' => 'Plugin GPTConfig',
//                         ],
//                     ],
//                 ],
                
//             ],
        ],
        'INDEXFIELDS' => [
            "domain",
        ]
    ];
}
