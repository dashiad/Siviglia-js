<?php

namespace sites\metadata\routes\Definitions;


class Pages
{
    static $definition = array(
        "indexForms" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinition"]),
        "indexFormsField" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinitionField"]),
        "indexModel" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"modelDefinition"]),
        "indexModelField" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"modelDefinitionField"])
    );
}
