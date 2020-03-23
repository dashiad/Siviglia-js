<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
include_once(__DIR__."/BaseType.php");
class Password extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Password"],
                "PASSWORD_ENCODING"=>["TYPE"=>"String","LABEL"=>"Codificación","DEFAULT"=>"BCRYPT","REQUIRED"=>true],
                "COST"=>["TYPE"=>"Integer","LABEL"=>"Coste","REQUIRED"=>true],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>BaseType::getSourceMeta()
            ]
        ];
    }

}
