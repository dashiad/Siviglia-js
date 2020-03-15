<?php namespace model\reflection\Types\types\meta;
class ModelField extends \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"ModelField"],
                "MODEL"=>["TYPE"=>"String",
                    "REQUIRED"=>true,
                    "SOURCE"=>[
                        "TYPE"=>"DataSource",
                        "MODEL"=>'\model\reflection\Model',
                        "DATASOURCE"=>'ModelList',
                        "LABEL"=>"[%package%] > [%smallName%]",
                        "VALUE"=>"fullName"
                          ]],
                "FIELD"=>["TYPE"=>"String",
                          "SOURCE"=>[
                              "TYPE"=>"DataSource",
                              "MODEL"=>'\model\reflection\Model',
                              "DATASOURCE"=>'FieldList',
                              "PARAMS"=>[
                                "model"=>"[%../MODEL%]"
                              ],
                              "LABEL"=>"name",
                              "VALUE"=>"name"
                          ],
                          "REQUIRED"=>true
                    ],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false]
            ]
        ];
    }
}
