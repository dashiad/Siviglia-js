<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class TreePath extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"TreePath"],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false]
            ]
        ];
    }

}
