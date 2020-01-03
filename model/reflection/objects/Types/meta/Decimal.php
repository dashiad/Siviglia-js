<?php namespace model\reflection\Types\meta;
class Decimal extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Decimal"],
                "NINTEGERS"=>["LABEL"=>"Número de enteros","TYPE"=>"Integer","REQUIRED"=>true],
                "NDECIMALS"=>["LABEL"=>"Número de decimales","TYPE"=>"Integer","REQUIRED"=>true],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","SET_ON_EMPTY"=>false],
            ]
        ];
    }

}
