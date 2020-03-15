<?php namespace model\reflection\Types\types\meta;
class Timestamp extends \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Timestamp"],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false]
            ]
        ];
    }

}
