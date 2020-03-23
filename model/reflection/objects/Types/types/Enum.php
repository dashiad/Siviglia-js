<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class Enum extends \model\reflection\Types\BaseReflectionType
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
                        "TYPE"=>"String"
                    ]
                ],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false],
                "DEFAULT"=>["TYPE"=>"String",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        "PATH"=>"#../VALUES/[[SOURCE]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"LABEL"
                    ]
                ]
            ]
        ];
    }
}
