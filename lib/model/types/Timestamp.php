<?php
namespace lib\model\types;
class Timestamp extends BaseType
{
        function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
        {
                $def["TYPE"]="Timestamp";
                $def["DEFAULT"]="NOW";
                BaseType::__construct($name,$def,$parentType, $value,$validationMode);
                //$this->flags |= BaseType::TYPE_NOT_EDITABLE;
                $this->flags |= BaseType::TYPE_SET_ON_ACCESS;
        }
        function _validate($v)
        {
            return true;
        }
        function _setValue($v,$validationMode=null)
        {
            if($v==="NOW")
                $this->value=time();
            else
                $this->value=intval($v);
            $this->valueSet=true;
        }
        function _getValue()
        {
            if(!$this->valueSet)
                return time();
            return $this->value;
        }
        function _equals($value)
        {
            return $this->value==$value;
        }
        function _copy($type)
        {
            $this->apply($type->getValue());
        }
}
