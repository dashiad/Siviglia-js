<?php
namespace lib\model\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.
class Enum extends BaseType
{
    function _validate($val)
    {
        if(!is_numeric($val))
        {
            if(!in_array($val,$this->__definition["VALUES"]))
            {
                throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$val],$this);
            }

            $val=array_search($val,$this->__definition["VALUES"]);
        }


        if(!isset($this->__definition["VALUES"][$val]))
            throw new BaseTypeException(BaseTypeException::ERR_INVALID,["val"=>$val],$this);
        return true;
    }

    function _setValue($val,$validationMode=null)
    {
        $this->valueSet=true;
        if(!is_numeric($val))
        {
            $this->value=array_search($val,$this->__definition["VALUES"]);
        }
        else
            $this->value=intval($val);
    }
    function _getValue()
    {
        return $this->value;
    }
    function getLabels()
    {
        return $this->__definition["VALUES"];
    }
    function hasSource()
    {
        return true;
    }
    function __getSource($validating=false)
    {

        return \lib\model\types\sources\SourceFactory::getSource($this,["TYPE"=>"Array",
                "VALUES"=>$this->__definition["VALUES"]
                ],$validating);
    }
    function __checkSource($value)
    {
        // El tipo enum chequea su fuente en _validate.
        return true;
    }
    function __getDefaultValue()
    {
        if(isset($this->__definition["DEFAULT"]))
        {
            return $this->getValueFromLabel($this->__definition["DEFAULT"]);
        }
        return null;
    }
    function getValueFromLabel($label)
    {
        $pos=array_search($label,$this->__definition["VALUES"]);
        if($pos===false)
            throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$label],$this);
        return $pos;
    }
    function getLabelFromValue($value)
    {
        return $this->__definition["VALUES"][$value];
    }
    function getLabel()
    {
        if(!$this->__hasOwnValue())
        {
            if($this->__hasDefaultValue())
                return $this->__definition["DEFAULT"];
            return "";
        }
        return $this->__definition["VALUES"][$this->value];
    }
    function _equals($value)
    {
        return ((string)$this->value==(string)$value);
    }
    function _copy($value)
    {
        $this->valueSet=$value->valueSet;
        $this->value=$value->value;
    }

}
