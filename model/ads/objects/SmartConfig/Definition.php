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
                'REQUIRED' => true
            ],
            'regex' => [
                'TYPE' => 'String',
                'LABEL' => 'Regex',
                'REQUIRED' => true
            ],
            'plugin' => [
                'TYPE' => 'String',
                'LABEL' => 'Plugin',
                'REQUIRED' => true
            ],
            'config' => [
                'LABEL' => 'config',
                'TYPE' => 'Container',
                'KEEP_KEY_ON_EMPTY' => false,
                'FIELDS' => [
                    'Exelate' => [
                        'TYPE' => 'Dictionary',
                        'LABEL' => 'Plugin Exelate',
                        "VALUETYPE" => [
                            'TYPE' => "String",
                        ],
                    ],
                    'Adobe' => [
                        "TYPE" => "Container",
                        "LABEL" => "Plugin Adobe",
                        "FIELDS" => [
                            "name" => [
                                "TYPE" => "String",
                            ],
                            "ignoreCMP" => [
                                "TYPE" => "Boolean",
                                "LABEL" => "Ignorar CMP",
                            ],
                            "segments" => [
                                'TYPE' => 'Dictionary',
                                'LABEL' => 'Segmentos',
                                "VALUETYPE" => [
                                    'TYPE' => "String",
                                ],
                            ],
                        ],
                    ],
                    "BlueKaiPlugin" => [
                        "TYPE" => "Container",
                        "LABEL" => "Plugin Bluekai",
                        "FIELDS" => [
                            "siteId" => [
                                "LABEL" => "Site Id",
                                "TYPE" => "String"
                            ],
                            "segments" => [
                                "TYPE" => "Dictionary",
                                "LABEL" => "Segmentos",
                                "VALUETYPE" => [
                                    'TYPE' => "String",
                                ],
                            ]
                        ]
                    ],
                    'ImageLoaderUrl' => [
                        'TYPE' => 'Container',
                        'FIELDS' => [
                            'url' => [
                                'LABEL' => 'URL',
                                'TYPE' => '_String',
                                'REQUIRED' => true
                            ],
                            'throttle' => [
                                'LABEL' => 'Throttle',
                                'TYPE' => 'Integer',
                                'REQUIRED' => true,
                                'DEFAULT' => 1
                            ]
                        ]
                    ],
                    "AdnSegments" => [
                        "TYPE" => "Container",
                        "LABEL" => "Segmentos de AppNexus.Introducir los ids de segmentos asociados a la url actual",
                        "FIELDS" => [
                            "segments" => [
                                "TYPE" => "Array",
                                "LABEL" => "Ids de segmentos",
                                "ELEMENTS" => [
                                    "TYPE" => "String"
                                ]
                            ]
                        ]
                    ],
                    "GPTConfig" => [
                        "TYPE" => "Container",
                        "DESCRIPTION" => "Configuración de header bidding, logging y  slots de publicidad de la página.Requiere que SmartclipConfig sea cargado en la cabecera de la página, y no desde las creatividades",
                        "FIELDS" => [
                            "log" => [
                                "TYPE" => "Boolean",
                                "LABEL" => "Activar log a Kibana"
                            ],
                            "logProbability" => [
                                "TYPE" => "Integer",
                                "LABEL" => "Log probability",
                            ],
                            "prebid" => [
                                "TYPE" => "Container",
                                "DESCRIPTION" => "Configuracion de Header bidding",
                                "LABEL" => "Header bidding",
                                "FIELDS" => [
                                    "autoload" => [
                                        "TYPE" => "Boolean",
                                        "DEFAULT" => true,
                                        "LABEL" => "Precargar prebid.js"
                                    ],
                                    "bidders" => [
                                        'TYPE' => 'Array',
                                        'ELEMENTS' => [
                                            "TYPE" => "TypeSwitcher",
                                            "LABEL" => "Bidders",
                                            'TYPE_FIELD' => 'bidder_type',
                                            "ALLOWED_TYPES" => [
                                                "appnexus" => [
                                                    "TYPE" => "Container",
                                                    "FIELDS" => [
                                                        "bidder" => [
                                                            "TYPE" => "\\model\\ads\\SmartConfig\\types\\AppnexusBidder",
                                                        ],
                                                    ],
                                                ],
                                                "aol" => [
                                                    "TYPE" => "Container",
                                                    "FIELDS" => [
                                                        "bidder" => [
                                                            "TYPE" => "\\model\\ads\\SmartConfig\\types\\AolBidder",
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]
                                ]
                            ],
                            "slots" => [
                                "TYPE" => "Dictionary",
                                "DESCRIPTION" => "Configuración de los slots gpt, segun div-id",
                                "VALUETYPE" => [
                                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
                                ]
                            ],
                            "sizes" => [
                                "TYPE" => "Dictionary",
                                "DESCRIPTION" => "Configuración de los slots gpt, segun tamaños",
                                "VALUETYPE" => [
                                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
                                ]
                            ],
                            "adunits" => [
                                "TYPE" => "Dictionary",
                                "DESCRIPTION" => "Configuración de los slots gpt, segun adunits",
                                "VALUETYPE" => [
                                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ];
    
//     static $definition = [
//         'ROLE' => 'ENTITY',
//         'DEFAULT_SERIALIZER' => 'smartconfig',
//         'DEFAULT_WRITE_SERIALIZER' => 'smartconfig',
//         'FIELDS' => [
//             'configType' => [
//                 'TYPE' => 'String',
//                 'LABEL' => 'Tipo de configuración',
//                 'REQUIRED' => true,
//                 'DEFAULT' => 'default',
//             ],
//             'domain' => [
//                 'TYPE' => 'String',
//                 'LABEL' => 'Dominio',
//                 'REQUIRED' => true
//             ],
//             'regex' => [
//                 'TYPE' => 'String',
//                 'LABEL' => 'Regex',
//                 'REQUIRED' => true
//             ],
//             'name' => [
//                 'TYPE' => 'String',
//                 'SOURCE' => [
//                     [
//                         "TYPE"=>"Path", 
//                         //"PATH"=>"#../plugin/[[KEYS]]",
//                         "PATH"=>"#../plugin/name",
//                         //"VALUE"=>"TYPE",
//                     ],
//                 ],
//             ],
//             'plugin' => [
//                 'LABEL' => 'Plugin',
//                 'TYPE' => 'TypeSwitcher',
//                 'TYPE_FIELD' => 'name',
//                 'ALLOWED_TYPES' => [
//                     /*'Exelate' => [
//                         'TYPE' => 'Container',
//                         'LABEL' => 'Plugin Exelate',
//                         'FIELDS' => [
//                             'content' => [
//                                 'TYPE' => 'Dictionary',
//                                 'LABEL' => 'Campos',
//                                 "VALUETYPE" => [
//                                     'TYPE' => "String",
//                                 ],
//                             ],
//                         ],*/
//                     'Exelate' => [
//                         'TYPE' => 'Dictionary',
//                         'LABEL' => 'Plugin Exelate',
//                             "VALUETYPE" => [
//                                 'TYPE' => "String",
//                             ],
//                     ],
//                     'Adobe' => [
//                         "TYPE" => "Container",
//                         "LABEL" => "Plugin Adobe",
//                         "FIELDS" => [
//                             "name" => [
//                                 "TYPE" => "String",
//                             ],
//                             "ignoreCMP" => [
//                                 "TYPE" => "Boolean",
//                                 "LABEL" => "Ignorar CMP",
//                             ],
//                             "segments" => [
//                                 'TYPE' => 'Dictionary',
//                                 'LABEL' => 'Segmentos',
//                                 "VALUETYPE" => [
//                                     'TYPE' => "String",
//                                 ],
//                             ],
//                         ],
//                     ],
//                     "BlueKaiPlugin" => [
//                         "TYPE" => "Container",
//                         "LABEL" => "Plugin Bluekai",
//                         "FIELDS" => [
//                             "siteId" => [
//                                 "LABEL" => "Site Id",
//                                 "TYPE" => "String"
//                             ],
//                             "segments" => [
//                                 "TYPE" => "Dictionary",
//                                 "LABEL" => "Segmentos",
//                                 "VALUETYPE" => [
//                                     'TYPE' => "String",
//                                 ],
//                             ]
//                         ]
//                     ],
//                     'ImageLoaderUrl' => [
//                         'TYPE' => 'Container',
//                         'FIELDS' => [
//                             'url' => [
//                                 'LABEL' => 'URL',
//                                 'TYPE' => '_String',
//                                 'REQUIRED' => true
//                             ],
//                             'throttle' => [
//                                 'LABEL' => 'Throttle',
//                                 'TYPE' => 'Integer',
//                                 'REQUIRED' => true,
//                                 'DEFAULT' => 1
//                             ]
//                         ]
//                     ],
//                     "AdnSegmentsPlugin" => [
//                         "TYPE" => "Container",
//                         "LABEL" => "Segmentos de AppNexus.Introducir los ids de segmentos asociados a la url actual",
//                         "FIELDS" => [
//                             "segments" => [
//                                 "TYPE" => "Array",
//                                 "LABEL" => "Ids de segmentos",
//                                 "ELEMENTS" => [
//                                     "TYPE" => "String"
//                                 ]
//                             ]
//                         ]
//                     ],
//                     "GPTPlugin" => [
//                         "TYPE" => "Container",
//                         "DESCRIPTION" => "Configuración de header bidding, logging y  slots de publicidad de la página.Requiere que SmartclipConfig sea cargado en la cabecera de la página, y no desde las creatividades",
//                         "FIELDS" => [
//                             "log" => [
//                                 "TYPE" => "Boolean",
//                                 "LABEL" => "Activar log a Kibana"
//                             ],
//                             "logProbability" => [
//                                  "TYPE" => "Integer",
//                                  "LABEL" => "Log probability",
//                             ],
//                             "prebid" => [
//                                 "TYPE" => "Container",
//                                 "DESCRIPTION" => "Configuracion de Header bidding",
//                                 "LABEL" => "Header bidding",
//                                 "FIELDS" => [
//                                     "autoload" => [
//                                         "TYPE" => "Boolean",
//                                         "DEFAULT" => true,
//                                         "LABEL" => "Precargar prebid.js"
//                                     ],
//                                     "bidders" => [
//                                         'TYPE' => 'Array',
//                                         'ELEMENTS' => [
//                                             "TYPE" => "TypeSwitcher",
//                                             "LABEL" => "Bidders",
//                                             'TYPE_FIELD' => 'bidder_type',
//                                             "ALLOWED_TYPES" => [
//                                                 "appnexus" => [
//                                                     "TYPE" => "Container",
//                                                     "FIELDS" => [
//                                                         "bidder" => [
//                                                             "TYPE" => "\\model\\ads\\SmartConfig\\types\\AppnexusBidder",
//                                                          ],
//                                                     ],
//                                                 ],
//                                                 "aol" => [
//                                                     "TYPE" => "Container",
//                                                     "FIELDS" => [
//                                                         "bidder" => [
//                                                             "TYPE" => "\\model\\ads\\SmartConfig\\types\\AolBidder",
//                                                         ],
//                                                     ],
//                                                 ],
//                                             ],
//                                         ],
//                                     ]
//                                 ]
//                             ],
//                             "slots" => [
//                                 "TYPE" => "Dictionary",
//                                 "DESCRIPTION" => "Configuración de los slots gpt, segun div-id",
//                                 "VALUETYPE" => [
//                                     "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
//                                 ]
//                             ],
//                             "sizes" => [
//                                 "TYPE" => "Dictionary",
//                                 "DESCRIPTION" => "Configuración de los slots gpt, segun tamaños",
//                                 "VALUETYPE" => [
//                                     "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
//                                 ]
//                             ],
//                             "adunits" => [
//                                 "TYPE" => "Dictionary",
//                                 "DESCRIPTION" => "Configuración de los slots gpt, segun adunits",
//                                  "VALUETYPE" => [
//                                      "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot",
//                                  ]
//                             ]
//                         ]
//                     ],
//                 ]
//             ]
//         ]
//     ];
}