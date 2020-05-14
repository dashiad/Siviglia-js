<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class Decimal extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Decimal",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Decimal"],
                "NINTEGERS"=>["LABEL"=>"Número de enteros","TYPE"=>"Integer","REQUIRED"=>true],
                "NDECIMALS"=>["LABEL"=>"Número de decimales","TYPE"=>"Integer","REQUIRED"=>true],
                "DEFAULT"=>["TYPE"=>"Decimal","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
            ],$parentType,$value,$validationMode);

    }

}
