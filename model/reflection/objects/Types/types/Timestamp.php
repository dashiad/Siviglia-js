<?php namespace model\reflection\Types\types;

class Timestamp extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Timestamp"],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false]
            ]
        ]);

    }

}
