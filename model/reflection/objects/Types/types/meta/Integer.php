<?php namespace model\reflection\Types\types\meta;
include_once(__DIR__."/BaseType.php");
class Integer extends \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Integer"],
                "MIN"=>["TYPE"=>"Integer","LABEL"=>"Valor mínimo","SET_ON_EMPTY"=>false],
                "MAX"=>["TYPE"=>"Integer","LABEL"=>"Valor máximo","SET_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","SET_ON_EMPTY"=>false],
                "SOURCE"=>BaseType::getSourceMeta()
            ]
        ];
    }

}
