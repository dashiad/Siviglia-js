<?php namespace lib\storage\Mysql\types;
class UUID extends BaseType {
    function serialize($name,$type,$serializer,$model=null)
    {
        $val=$type->getValue();
        return [$name=>"'".$val."'"];
    }
    function getSQLDefinition($fieldName,$definition,$serializer)
    {
        return array("NAME"=>$fieldName,"TYPE"=>"varchar(36)");
    }
}
?>
