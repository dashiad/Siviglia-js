<?php


namespace model\reflection;

include_once(__DIR__."/types/ModelDatasourceReference.php");
use model\reflection\Types\types\ModelDatasourceReference;

class Types
{

    static function geCommonMeta()
    {
        return [
            "FIXED"=>["TYPE"=>"String"],
            "DEFAULT"=>["TYPE"=>"String"]
        ];
    }
    static function getSourceMeta()
    {
        $datasourceReference=new ModelDatasourceReference();
        $dsMeta=$datasourceReference->getDefinition();
        $dsMeta["PARAMS"]=[
            "LABEL"=>"Parameters",
            "TYPE"=>"DICTIONARY",
            "VALUETYPE"=>[
                "TYPE"=>"String"
            ]
        ];

        return [
            "LABEL"=>"Source",
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "ALLOWED_TYPES"=>[
                "Array"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["TYPE"=>"String","FIXED"=>"Array"],
                        "DATA"=>["TYPE"=>"Array",
                            "ELEMENTS"=>[
                                "TYPE"=>"CONTAINER",
                                "REQUIRED"=>true,
                                "FIELDS"=>[
                                    "Id"=>["TYPE"=>"Integer"],
                                    "Label"=>["TYPE"=>"String"],
                                    "Extra"=>["TYPE"=>"String"]
                                ]
                            ],
                            "PATH"=>["TYPE"=>"String"]
                        ]
                    ]
                ],
                "DataSource"=>[
                    "TYPE"=>"Container",
                    "FIELDS"=>$dsMeta
                ],
                "Path"=>[
                    "TYPE"=>"String",
                    "REQUIRED"=>true
                ]
            ]

        ];
    }
}