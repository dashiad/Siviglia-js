<?php namespace model\reflection\Types\meta;
include_once(PROJECTPATH."/model/reflection/objects/base/BaseMetadata.php");

class BaseType extends \model\reflection\Meta\BaseMetadata
{
    function geCommonMeta()
    {
        return [
            "FIXED"=>["TYPE"=>"String"],
            "DEFAULT"=>["TYPE"=>"String"]
        ];
    }
    function getSourceMeta()
    {
        $datasourceReference=$this->importDefinition(
            "types/ModelDatasourceReference",
            'types\ModelDatasourceReference'
        );
        $datasourceReference["PARAMS"]=[
            "TYPE"=>"DICTIONARY",
            "VALUETYPE"=>[
                "TYPE"=>"String"
            ]
        ];

        return [
            "SOURCE"=>[
                "TYPE"=>"TYPESWITCHER",
                "TYPE_FIELD"=>"TYPE",
                "ALLOWED_TYPES"=>[
                    "Array"=>[
                        "TYPE"=>"CONTAINER",
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
                                     ]
                            ]
                        ]
                    ],
                    "DataSource"=>[
                        "TYPE"=>"CONTAINER",
                        "FIELDS"=>$datasourceReference
                    ],
                    "Path"=>[
                        "TYPE"=>"STRING",
                        "REQUIRED"=>true
                    ]
                ]
            ]
        ];
    }
    function getMeta()
    {

    }
}