<?php

namespace sites\adtopy\routes\Definitions;


class Pages
{
    static $definition = array(
        "indexForms" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinition"]),
        "indexFormsField" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinitionField"])
    );
}
