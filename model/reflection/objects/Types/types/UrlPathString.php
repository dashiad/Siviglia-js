<?php
namespace model\reflection\Types\types;

class UrlPathString extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"UrlPathString",
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["TYPE" => "String", "FIXED" => "UrlPathString"],
                "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "KEEP_KEY_ON_EMPTY" => false],
                "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false],
                "DEFAULT" => ["TYPE" => "String", "LABEL" => "Valor por defecto", "KEEP_KEY_ON_EMPTY" => false]
            ]
        ]);

    }

}
