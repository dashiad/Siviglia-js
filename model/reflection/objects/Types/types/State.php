<?php namespace model\reflection\Types\types;

class State extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"State"],
                "VALUES"=>[
                    "LABEL"=>"Lista de estados",
                    "TYPE"=>"Array",
                    "ELEMENTS"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "LABEL"=>["LABEL"=>"Etiqueta","TYPE"=>"String"],
                            "VALUE"=>["LABEL"=>"Valor","TYPE"=>"Integer"]

                        ]
                    ]
                ],
                "REQUIRED"=>["LABEL"=>"Requerido","TYPE"=>"Boolean","DEFAULT"=>false],
                "DEFAULT"=>["TYPE"=>"String",
                    "LABEL"=>"Valor por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../VALUES/[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"LABEL"
                    ]
                ],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false]
            ]
        ],$parentType,$value,$validationMode);

    }

}
