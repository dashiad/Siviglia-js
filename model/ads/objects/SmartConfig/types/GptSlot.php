<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class GptSlot extends  BaseType
{
    
    public $definition = [
        //'ROLE' => 'ENTITY',
        'DEFAULT_SERIALIZER' => 'smartconfig',
        'DEFAULT_WRITE_SERIALIZER' => 'smartconfig',
        "FIELDS" => [
            "schedule" => [
                "LABEL" => "Prioridad",
                "TYPE"  => "\\model\\ads\\SmartConfig\\types\\Scheduling"
            ],
            "headerBidding" => [
                "DESCRIPTION" => "Para activar header bidding en este slot, asigna un tiempo de espera para las pujas",
                "TYPE" => "Container",
                "FIELDS" => [
                    "timeout" => [
                        "LABEL" => "Timeout",
                        "TYPE" => "String"
                    ]
                ]
            ],
            "reload" => [
                "DESCRIPTION" => "Tiempo en milisegundos entre recargas del slot",
                "TYPE" => "String",
                "LABEL" => "Reload"
            ],
            "relocate" => [
                "DESCRIPTION" => "Tiempo en milisegundos antes de mostrar anuncios compatibles de otros slots, en este slot.",
                "TYPE" => "String",
                "LABEL" => "Relocate"
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

