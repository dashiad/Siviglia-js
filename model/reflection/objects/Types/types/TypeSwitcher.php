<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class TypeSwitcher extends \model\reflection\Types\BaseReflectionType
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
        ];
    }
}
