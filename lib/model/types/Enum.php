<?php
namespace lib\model\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.
class Enum extends BaseType
{
    function _validate($val)
    {
        if(!is_numeric($val))
        {
            if(!in_array($val,$this->definition["VALUES"]))
            {
                throw new BaseTypeException(BaseTypeException::ERR_INVALID,["val"=>$val],$this);
            }

            $val=array_search($val,$this->definition["VALUES"]);
        }


        if(!isset($this->definition["VALUES"][$val]))
            throw new BaseTypeException(BaseTypeException::ERR_INVALID,["val"=>$val],$this);
        return true;
    }

    function _setValue($val)
    {
        $this->valueSet=true;
        if(!is_numeric($val))
        {
            $this->value=array_search($val,$this->definition["VALUES"]);
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
        return $this->definition["VALUES"];
    }
    function hasSource()
    {
        return true;
    }
    function getSource($validating=false)
    {

        return \lib\model\types\sources\SourceFactory::getSource($this,["TYPE"=>"Array",
                "VALUES"=>$this->definition["VALUES"]
                ],$validating);
    }
    function checkSource($value)
    {
        // El tipo enum chequea su fuente en _validate.
        return true;
    }
    function getDefaultValue()
    {
        if(isset($this->definition["DEFAULT"]))
        {
            return $this->getValueFromLabel($this->definition["DEFAULT"]);
        }
        return null;
    }
    function getValueFromLabel($label)
    {
        $pos=array_search($label,$this->definition["VALUES"]);
        if($pos===false)
            throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$label],$this);
        return $pos;
    }
    function getLabelFromValue($value)
    {
        return $this->definition["VALUES"][$value];
    }
    function getLabel()
    {
        if(!$this->hasOwnValue())
        {
            if($this->hasDefaultValue())
                return $this->definition["DEFAULT"];
            return "";
        }
        return $this->definition["VALUES"][$this->value];
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
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Enum.php");
        return '\model\reflection\Types\meta\Enum';
    }
}
