<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class BankAccount extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "BankAccount",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"BankAccount"],
                "DEFAULT"=>["LABEL"=>"Valor por defecto","TYPE"=>"String","KEEP_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
