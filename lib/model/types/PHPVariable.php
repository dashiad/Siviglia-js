<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 16/06/14
 * Time: 23:15
 */
namespace lib\model\types;
class PHPVariableException extends BaseTypeException {

}

class PHPVariable extends \lib\model\types\BaseType {

    function setValue($val)
    {
        parent::setValue($val);
    }

    function getUnserializedValue()
    {
        if($this->valueSet)
            return unserialize($this->value);
        if($this->hasDefaultValue())
            return unserialize($this->getDefaultValue());
        return null;
    }
}

class PHPVariableMYSQLSerializer extends BaseTypeMYSQLSerializer
{
    function serialize($type)
    {
        if($type->hasValue())
            return "'".mysql_escape_string(serialize($type->getValue()))."'";
        else
            return "NULL";
    }
    function unserialize($type,$value)
    {
        if($value)
        {
            $type->setValue(unserialize($value));
        }

    }
    function getSQLDefinition($name,$definition)
    {
        return array("NAME"=>$name,"TYPE"=>"BLOB");
    }
}
class PHPVariableHTMLSerializer extends BaseTypeHTMLSerializer
{
    function serialize($type)
    {
        if($type->hasValue())
            return json_encode($type->value);
        return '';
    }
}