<?php
namespace model\ads\SmartConfig\types;

use lib\model\types\BaseType;

require_once(__DIR__."/BaseType.php");

class AdnSegments extends BaseType
{
    
    public $definition = [
        "TYPE" => "Container",
        "LABEL" => 'Segmentos',
        "DESCRIPTION" => "Segmentos de AppNexus.Introducir los ids de segmentos asociados a la url actual",
        "FIELDS" => [
            "segments" => [
                "TYPE" => "Array",
                "LABEL" => "Ids de segmentos",
                "ELEMENTS" => [
                    "TYPE" => "String"
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
