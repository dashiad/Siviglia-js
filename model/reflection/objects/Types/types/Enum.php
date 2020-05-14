<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class Enum extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Enum",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Enum"],
                "VALUES"=>[
                    "LABEL"=>"Valores permitidos",
                    "TYPE"=>"Array",
                    "ELEMENTS"=>[
                        "TYPE"=>"String"
                    ]
                ],
                "DEFAULT"=>["TYPE"=>"String",
                    "LABEL"=>"Valor por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../VALUES/[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"VALUE"
                    ]
                ]
            ],$parentType,$value,$validationMode);

    }
}
