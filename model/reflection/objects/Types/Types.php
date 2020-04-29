<?php


namespace model\reflection;

include_once(__DIR__."/types/ModelDatasourceReference.php");
use model\reflection\Types\types\ModelDatasourceReference;

class Types
{

    static function geCommonMeta()
    {
        return [
            "FIXED"=>["LABEL"=>"Fijo","TYPE"=>"String"],
            "DEFAULT"=>["LABEL"=>"Valor por defect","TYPE"=>"String"]
        ];
    }
    static function getSourceMeta()
    {
        $datasourceReference=new ModelDatasourceReference();
        $dsMeta=$datasourceReference->getDefinition();
        /*$dsMeta["PARAMS"]=[
            "LABEL"=>"Parameters",
            "TYPE"=>"DICTIONARY",
            "VALUETYPE"=>[
                "LABEL"=>"Valor",
                "TYPE"=>"String"
            ]
        ];*/

        return [
            "LABEL"=>"Source",
            "TYPE"=>"TypeSwitcher",
            "TYPE_FIELD"=>"TYPE",
            "ALLOWED_TYPES"=>[
                "Array"=>[
                    "LABEL"=>"Array",
                    "TYPE"=>"Container",
                    "FIELDS"=>[
                        "TYPE"=>["LABEL"=>"Tipo","TYPE"=>"String","FIXED"=>"Array"],
                        "DATA"=>[
                            "LABEL"=>"Data",
                            "TYPE"=>"Array",
                            "ELEMENTS"=>[
                                "LABEL"=>"Elementos",
                                "TYPE"=>"Container",
                                "REQUIRED"=>true,
                                "FIELDS"=>[
                                    "Id"=>["TYPE"=>"Integer","LABEL"=>"Id","REQUIRED"=>true],
                                    "Label"=>["TYPE"=>"String","LABEL"=>"Label","REQUIRED"=>true],
                                    "Extra"=>["TYPE"=>"String","LABEL"=>"Extra"]
                                ]
                            ],
                            "PATH"=>["LABEL"=>"Path","TYPE"=>"String"]
                        ]
                    ]
                ],
                "DataSource"=>[
                    "LABEL"=>"Datasource",
                    "TYPE"=>"Container",
                    "FIELDS"=>$dsMeta["FIELDS"]
                ],
                "Path"=>[
                    "LABEL"=>"Path",
                    "TYPE"=>"String"
                ]
            ]

        ];
    }
}