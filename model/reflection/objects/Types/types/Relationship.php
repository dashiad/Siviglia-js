<?php namespace model\reflection\Types\types;


class Relationship extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Relationship"],
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
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta()
            ]
        ]);

    }
}
