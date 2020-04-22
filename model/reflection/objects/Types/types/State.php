<?php namespace model\reflection\Types\types;

class State extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"State"],
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
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false]
            ]
        ]);

    }

}
