<?php namespace model\reflection\Types\meta;
class Relationship extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Relationship"],
                "MODEL"=>["TYPE"=>"String",
                    "REQUIRED"=>true,
                    "SOURCE"=>[
                        "TYPE"=>"DataSource",
                        "MODEL"=>'\model\reflection\Model',
                        "DATASOURCE"=>'ModelList',
                        "LABEL"=>"[%package%] > [%smallName%]",
                        "VALUE"=>"fullName"
                    ]],
                "FIELDS"=>["TYPE"=>"Dictionary",
                    "SOURCE"=>[
                        "TYPE"=>"PathSource",
                        "PATH"=>"[%../../FIELDS%]"
                    ],
                    "VALUETYPE"=>[
                        "TYPE"=>"String",
                        "SOURCE"=>[
                            "TYPE"=>"DataSource",
                            "MODEL"=>'\model\reflection\Model',
                            "DATASOURCE"=>'FieldList',
                            "PARAMS"=>[
                                "model"=>"[%../MODEL%]"
                            ],
                            "LABEL"=>"name",
                            "VALUE"=>"name"
                        ]
                    ],
                    "REQUIRED"=>true

                    ],
                "MULTIPLICITY"=>["LABEL"=>"Multiplicidad","TYPE"=>"Enum","VALUES"=>["1:1","1:N","M:N"]],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","SET_ON_EMPTY"=>false],
                "SOURCE"=>BaseType::getSourceMeta()
            ]
        ];
    }
}
