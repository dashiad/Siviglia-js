<?php
namespace model\ads\SmartConfig\types;

require_once(__DIR__."/BaseType.php");

class Domain extends BaseType
{
    
    const REGEX = "/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/m";
    
    public function _setValue($val, $validationMode = null)
    {
        $this->value = $val;
        $this->valueSet = true;
    }
    
    public function _validate($value)
    {
        return (preg_match(self::REGEX, $value)===1);
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

