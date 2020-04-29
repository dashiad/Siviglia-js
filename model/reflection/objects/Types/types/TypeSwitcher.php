<?php namespace model\reflection\Types\types;

class TypeSwitcher extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"TypeSwitcher"],
                "ALLOWED_TYPES"=>[
                    "TYPE"=>"Dictionary",
                    "LABEL"=>"Types",
                    "VALUETYPE"=>"/model/reflection/Model/types/BaseType",
                    "REQUIRED"=>true
                    ],
                "IMPLICIT_TYPE"=>["TYPE"=>"String",
                    "LABEL"=>"Tipo por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../ALLOWED_TYPES/[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"VALUE"
                    ],
                    "KEEP_KEY_ON_EMPTY"=>false
                    ],
                "TYPE_FIELD"=>[
                    "LABEL"=>"SubKey de tipo",
                    "TYPE"=>"String",
                    "DEFAULT"=>"TYPE",
                    "REQUIRED"=>true
                ],
                "CONTENT_FIELD"=>[
                    "LABEL"=>"SubKey de contenido",
                    "TYPE"=>"String",
                    "KEEP_KEY_ON_EMPTY"=>false
                ],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false]

            ]
        ]);

    }
}
