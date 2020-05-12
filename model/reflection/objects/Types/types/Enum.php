<?php namespace model\reflection\Types\types;

class Enum extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "LABEL"=>"Enum",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "LABEL"=>["LABEL"=>"Label","TYPE"=>"String"],
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Enum"],
                "VALUES"=>[
                    "LABEL"=>"Valores permitidos",
                    "TYPE"=>"Array",
                    "ELEMENTS"=>[
                        "TYPE"=>"String"
                    ]
                ],
                "REQUIRED"=>["LABEL"=>"Requerido","TYPE"=>"Boolean","DEFAULT"=>false],
                "DEFAULT"=>["TYPE"=>"String",
                    "LABEL"=>"Valor por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../VALUES/[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"VALUE"
                    ]
                ]
            ]
        ,$parentType,$value,$validationMode]);

    }
}
