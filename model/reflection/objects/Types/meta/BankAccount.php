<?php namespace model\reflection\Types\meta;
class BankAccount extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"BankAccount"],
                "DEFAULT"=>["TYPE"=>"String"],
                "SOURCE"=>BaseType::getSourceMeta()
            ]
        ];
    }

}