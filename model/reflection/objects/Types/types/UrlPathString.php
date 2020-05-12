<?php
namespace model\reflection\Types\types;

class UrlPathString extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "LABEL"=>"UrlPathString",
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["LABEL"=>"TYPE","TYPE" => "String", "FIXED" => "UrlPathString"],
                "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "KEEP_KEY_ON_EMPTY" => false],
                "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false],
                "DEFAULT" => ["TYPE" => "String", "LABEL" => "Valor por defecto", "KEEP_KEY_ON_EMPTY" => false]
            ]
        ,$parentType,$value,$validationMode]);

    }

}
