<?php namespace lib\storage\ES\types;
class UUID extends BaseType {

    function getSQLDefinition($fieldName,$definition,$serializer)
    {
        return array("NAME"=>$fieldName,"TYPE"=>["type"=>"keyword"]);
    }
}
?>
