<?php namespace model\reflection\Types\types;


class BankAccount extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"BankAccount",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"BankAccount"],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta(),
                "REQUIRED"=>["LABEL"=>"Requerido","TYPE"=>"Boolean","DEFAULT"=>false]
            ]
        ]);

    }

}
