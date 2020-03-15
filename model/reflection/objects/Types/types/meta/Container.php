<?php
namespace model\reflection\Types\types\meta;
include_once(__DIR__."/BaseType.php");
class Container extends \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["TYPE" => "String", "FIXED" => "Container"],
                "FIELDS"=>[
                    "LABEL"=>"Campos",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>"BASETYPE",
                    "SOURCE" => BaseType::getSourceMeta()
                ],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir claves nulas","TYPE" => "Boolean", "DEFAULT" => false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
            ]
        ];
    }
}
