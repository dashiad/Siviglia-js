<?php
namespace lib\model\types;

// Modela un tipo de dato que es la transformacion de una string, a otra que debe ser unica, y modificada para
// aparecer en links.

class UrlPathString extends _String
{
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        parent::__construct(
            $name,
            array("TYPE"=>"UrlPathString","ALLOWHTML"=>false,"TRIM"=>true,"MINLENGTH"=>1,"MAXLENGTH"=>100),
            $parentType, $value,$validationMode);

    }
}
