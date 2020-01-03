<?php namespace model\reflection\Types\meta;
class TypeSwitcher extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"TypeSwitcher"],
                "ALLOWED_TYPES"=>["TYPE"=>"Dictionary",
                    "LABEL"=>"Types",
                    "VALUETYPE"=>"BASETYPE",
                    "REQUIRED"=>true
                    ],
                "IMPLICIT_TYPE"=>["TYPE"=>"String",
                    "LABEL"=>"Tipo por defecto",
                    "SOURCE"=>[
                        "TYPE"=>"PathSource",
                        "PATH"=>"/../ALLOWED_TYPES/{keys}"
                    ],
                    "SET_ON_EMPTY"=>false
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
                    "SET_ON_EMPTY"=>false
                ],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false]

            ]
        ];
    }
}
