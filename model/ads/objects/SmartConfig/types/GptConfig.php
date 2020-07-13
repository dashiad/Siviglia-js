<?php

namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class GPTConfig extends BaseType
{
    
    public $definition = [
        "LABEL" => "GPTConfig",
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
                "MIN" => 0,
                "MAX" => 10,
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
                        'LABEL' => 'Bidders',
                        'ELEMENTS' => [
                            "TYPE" => "TypeSwitcher",
                            "LABEL" => "Bidders",
                            'TYPE_FIELD' => 'bidder_type',
                            "ALLOWED_TYPES" => [
                                "appnexus" => [
                                    "TYPE" => "Container",
                                    "LABEL" => "appnexus",
                                    "FIELDS" => [
                                        "bidder" => [
                                            "LABEL" => "bidder",
                                            "TYPE" => "\\model\\ads\\SmartConfig\\types\\AppnexusBidder"
                                        ]
                                    ]
                                ],
                                "aol" => [
                                    "TYPE" => "Container",
                                    "LABEL" => "aol",
                                    "FIELDS" => [
                                        "bidder" => [
                                            "LABEL" => "bidder",
                                            "TYPE" => "\\model\\ads\\SmartConfig\\types\\AolBidder"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "slots" => [
                "LABEL" => "Slots",
                "TYPE" => "Dictionary",
                "DESCRIPTION" => "Configuración de los slots gpt, segun div-id",
                "VALUETYPE" => [
                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot"
                ]
            ],
            "sizes" => [
                "LABEL" => "Sizes",
                "TYPE" => "Dictionary",
                "DESCRIPTION" => "Configuración de los slots gpt, segun tamaños",
                "VALUETYPE" => [
                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot"
                ]
            ],
            "adunits" => [
                "LABEL" => "Adunits",
                "TYPE" => "Dictionary",
                "DESCRIPTION" => "Configuración de los slots gpt, segun adunits",
                "VALUETYPE" => [
                    "TYPE" => "\\model\\ads\\SmartConfig\\types\\GptSlot"
                ]
            ]
        ]
    ];
    
    public function __construct($name, $parentType=null, $value=null, $validationMode=null) {
        parent::__construct($name, $this->definition, $parentType, $value, $validationMode);
    }
    public function _setValue($val, $validationMode = null)
    {
        $this->value = $val;
        $this->valueSet = true;
    }
    
    public function _validate($value)
    {
        return true;
    }
    
    public function _getValue()
    {
        return $this->value;
    }
    
    public function _equals($value)
    {
        return $this->value===$value;
    }
    
    public function getMetaClassName()
    {
        return self::class;
    }
    
    public function _copy($val)
    {
        $this->value = $val->getValue();
        $this->valueSet = true;
    }
}