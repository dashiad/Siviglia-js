<?php namespace model\reflection\Types\types;

class Decimal extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"Decimal",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Decimal"],
                "NINTEGERS"=>["LABEL"=>"Número de enteros","TYPE"=>"Integer","REQUIRED"=>true],
                "NDECIMALS"=>["LABEL"=>"Número de decimales","TYPE"=>"Integer","REQUIRED"=>true],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
            ]
        ]);

    }

}
