<?php
namespace lib\model\types;
class Timestamp extends DateTime
{
        function __construct($definition,$value=false)
        {
                $definition["TYPE"]="Timestamp";
                $definition["DEFAULT"]="NOW";
                DateTime::__construct($definition,$value);
                $this->flags |= BaseType::TYPE_NOT_EDITABLE;
        }
}
class TimestampMYSQLSerializer extends BaseTypeMYSQLSerializer
{
    function serialize($type)
    {
        if($type->hasValue())
            return "'".$type->getValue()."'";
        else
            return "'".$type->getValueFromTimestamp()."'";
    }
    function getSQLDefinition($name,$definition)
    {
        return array("NAME"=>$name,"TYPE"=>"DATETIME");
    }
}
