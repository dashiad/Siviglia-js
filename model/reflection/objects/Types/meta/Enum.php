<?php namespace model\reflection\Types\meta;
class Enum extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Enum"],
                "VALUES"=>[
                    "TYPE"=>"Array",
                    "ELEMENTS"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "VALUE"=>["TYPE"=>"Integer"],
                            "LABEL"=>["TYPE"=>"String"]
                        ]
                    ]
                ],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false],
                "DEFAULT"=>["TYPE"=>"Integer",
                    "SOURCE"=>[
                        "TYPE"=>"PATH",
                        "PATH"=>"/{keys}"
                    ]
                ]
            ]
        ];
    }
}
