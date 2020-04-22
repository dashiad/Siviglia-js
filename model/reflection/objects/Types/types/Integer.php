<?php namespace model\reflection\Types\types;


class Integer extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Integer"],
                "MIN"=>["TYPE"=>"Integer","LABEL"=>"Valor mínimo","KEEP_KEY_ON_EMPTY"=>false],
                "MAX"=>["TYPE"=>"Integer","LABEL"=>"Valor máximo","KEEP_KEY_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta()
            ]
        ]);

    }

}
