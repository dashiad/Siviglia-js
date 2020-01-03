<?php namespace model\reflection\Types\meta;
class BankAccount extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"BankAccount"],
                "DEFAULT"=>["TYPE"=>"String"],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "SOURCE"=>BaseType::getSourceMeta(),
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false]
            ]
        ];
    }

}
