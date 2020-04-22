<?php namespace model\reflection\Types\types;


class UUID extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"UUID",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"UUID"],
                "LEVEL"=>["TYPE"=>"Enum","LABEL"=>"Level","VALUES"=>[1,3,4,5],"DEFAULT"=>1],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta()
            ]
        ]);

    }

}
