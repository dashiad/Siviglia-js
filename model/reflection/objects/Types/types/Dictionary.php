<?php namespace model\reflection\Types\types;


class Dictionary extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"Dictionary",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Dictionary"],
                "VALUETYPE"=>"/model/reflection/Model/types/BaseType",
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false]
            ]
        ]);

    }

}
