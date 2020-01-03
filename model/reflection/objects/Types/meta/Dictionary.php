<?php namespace model\reflection\Types\meta;
class Dictionary extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Dictionary"],
                "VALUETYPE"=>"BASETYPE",
                "SOURCE"=>BaseType::getSourceMeta(),
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false]
            ]
        ];
    }

}
