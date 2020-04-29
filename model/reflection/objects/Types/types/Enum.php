<?php namespace model\reflection\Types\types;

class Enum extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"Enum",
            "TYPE"=>"Container",
            "FIELDS"=>[
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
        ]);

    }
}
