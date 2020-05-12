<?php namespace model\reflection\Types\types;

class TypeSwitcher extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
        parent::__construct($name,
            [
                "LABEL"=>"TypeSwitcher",
                "TYPE"=>"TypeSwitcher",
                "ON"=>[
                    ["FIELD"=>"TYPE_FIELD","IS"=>"Present","THEN"=>"BY_TYPE"],
                    ["FIELD"=>"ON","IS"=>"Present","THEN"=>"BY_COND"]
                ],
                "ALLOWED_TYPES"=>[
                    "BY_TYPE"=>[
                        [
                            "LABEL"=>"Por tipo",
                            "TYPE"=>"Container",
                            "FIELDS"=>[
                                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"TypeSwitcher"],
                                "ALLOWED_TYPES"=>[
                                    "TYPE"=>"Dictionary",
                                    "LABEL"=>"Types",
                                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference",
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
                        ]
                    ,
                    "BY_COND"=>
                        [
                            "TYPE"=>"Container",
                            "FIELDS"=>[
                                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"TypeSwitcher"],
                                "ALLOWED_TYPES"=>[
                                    "TYPE"=>"Dictionary",
                                    "LABEL"=>"Types",
                                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference",
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
                                "ON"=>[
                                    "LABEL"=>"Condiciones",
                                    "TYPE"=>"Array",
                                    "ELEMENTS"=>[
                                        "TYPE"=>"Container",
                                        "FIELDS"=>[
                                            "FIELD"=>["LABEL"=>"Campo","TYPE"=>"String"],
                                            "IS"=>["LABEL"=>"Es","TYPE"=>"Enum","VALUES"=>["String","Array","Object","Present","Not Present"]],
                                            "THEN"=>["LABEL"=>"Entonces","TYPE"=>"String","SOURCE"=>[
                                                "TYPE"=>"Path",
                                                "PATH"=>"#../../../ALLOWED_TYPES/[[KEYS]]",
                                                "LABEL"=>"LABEL",
                                                "VALUE"=>"VALUE"
                                            ]]
                                        ]
                                    ],
                                    "REQUIRED"=>true
                                ],
                                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false]
                            ]
                        ]
                        ]
                ]
            ]

        );
parent::__construct($name, [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"TypeSwitcher"],
                "ALLOWED_TYPES"=>[
                    "TYPE"=>"Dictionary",
                    "LABEL"=>"Types",
                    "VALUETYPE"=>"/model/reflection/Types/types/BaseType",
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
        ,$parentType,$value,$validationMode]);

    }
}
