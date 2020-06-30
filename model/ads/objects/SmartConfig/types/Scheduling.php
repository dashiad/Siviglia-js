<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class Scheduling extends  BaseType
{
    
    public $definition = [
        "LABEL" => "Scheduling",
        "TYPE" => "Container",
        'FIELDS' => [
            "when" => [
                "TYPE" => "Enum",
                "LABEL" => "Inicio",
                "VALUES" => [
                    "READY",
                    "LOAD"
                ]
            ],
            "timeout" => [
                "TYPE" => "String",
                "LABEL" => "Retardo (en milisegundos)"
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

