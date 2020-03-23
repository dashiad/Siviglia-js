<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
include_once(__DIR__."/BaseType.php");
class BankAccount extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"BankAccount"],
                "DEFAULT"=>["TYPE"=>"String"],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>BaseType::getSourceMeta(),
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false]
            ]
        ];
    }

}
