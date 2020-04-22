<?php namespace model\reflection\Types\types;

class Enum extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"Enum",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Enum"],
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
        ]);

    }
}
