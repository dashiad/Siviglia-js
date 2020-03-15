<?php namespace model\reflection\Types\types\meta;
class State extends \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"State"],
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
                ],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false]
            ]
        ];
    }

}
