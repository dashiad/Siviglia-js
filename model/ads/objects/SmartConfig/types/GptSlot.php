<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class GptSlot extends  BaseType
{
    
    public $definition = [
        'ROLE' => 'ENTITY',
        "LABEL" => "Gpt Slot",
        "DESCRIPTION" => "Slot GPT",
        "TYPE" => "Container",
//         'DEFAULT_SERIALIZER' => 'smartconfig',
//         'DEFAULT_WRITE_SERIALIZER' => 'smartconfig',
        "FIELDS" => [
            "schedule" => [
                "LABEL" => "Prioridad",
                "TYPE"  => "\\model\\ads\\SmartConfig\\types\\Scheduling"
            ],
            "headerBidding" => [
                "TYPE" => "Container",
                "LABEL" => "Header Bidding",
                "DESCRIPTION" => "Para activar header bidding en este slot, asigna un tiempo de espera para las pujas",
                "FIELDS" => [
                    "timeout" => [
                        "LABEL" => "Timeout",
                        "TYPE" => "String"
                    ]
                ]
            ],
            "reload" => [
                "TYPE" => "String",
                "LABEL" => "Reload",
                "DESCRIPTION" => "Tiempo en milisegundos entre recargas del slot",
            ],
            "relocate" => [
                "TYPE" => "String",
                "LABEL" => "Relocate",
                "DESCRIPTION" => "Tiempo en milisegundos antes de mostrar anuncios compatibles de otros slots, en este slot.",
            ],
            "log" => [
                "TYPE" => "Boolean",
                "LABEL" => "Activar log a Kibana",
                "DESCRIPTION" => "Enviar log de estos slots.Util cuando el log global (a nivel de plugin) está desactivado"
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

    public function _copy($val)
    {
        $this->value = $val->getValue();
        $this->valueSet = true;
    }
}

