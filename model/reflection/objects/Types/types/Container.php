<?php
namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Container extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Container", [
                "TYPE" => ["LABEL"=>"Tipo","TYPE" => "String", "FIXED" => "Container"],
                "FIELDS"=>[
                    "LABEL"=>"Campos",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference",
                ],
                "STATES" => [
                    "LABEL" => "Estados",
                    "TYPE" => "/model/reflection/Model/types/StateSpec",
                    "KEEP_ON_EMPTY" => false
                ]
            ],$parentType,$value,$validationMode);

    }
}
