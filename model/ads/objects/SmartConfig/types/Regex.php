<?php
namespace model\ads\SmartConfig\types;

require_once(__DIR__."/BaseType.php");

class Regex extends BaseType
{
    // TODO: regex para validar regex de rutas SmartConfig
    const REGEX = "";
    
    public $definition = [
        "TYPE" => "String",
        "DESCRIPTION" => "Regex",
        "LABEL" => "Regex",
    ];
    
    public function _setValue($val, $validationMode = null)
    {
        $this->value = $val;
        $this->valueSet = true;
    }
    
    public function _validate($value)
    {
        //return (preg_match(self::REGEX, $value)===1);
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

