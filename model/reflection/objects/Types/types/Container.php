<?php
namespace model\reflection\Types\types;


class Container extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "LABEL"=>"Container",
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["LABEL"=>"Tipo","TYPE" => "String", "FIXED" => "Container"],
                "FIELDS"=>[
                    "LABEL"=>"Campos",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference",
                ],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir claves nulas","TYPE" => "Boolean", "DEFAULT" => false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
            ]
        ,$parentType,$value,$validationMode]);

    }
}
