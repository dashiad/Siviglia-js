<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class State extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "State",[

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
                "DEFAULT"=>["TYPE"=>"String",
                    "LABEL"=>"Valor por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../VALUES/[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"LABEL"
                    ]
                ]
            ],$parentType,$value,$validationMode);

    }

}
