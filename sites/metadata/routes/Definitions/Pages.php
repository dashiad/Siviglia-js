<?php

namespace sites\metadata\routes\Definitions;


class Pages
{
    static $definition = array(
        "indexForms" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinition"]),
        "indexFormsField" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"formDefinitionField"]),
        "indexModel" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"modelDefinition"]),
        "indexModelField" => array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"modelDefinitionField"]),
        "indexDatasource"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"datasourceDefinition"]),
        "indexDatasourceField"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"datasourceDefinitionField"]),
        "indexDatasourceParams"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"datasourceParams"]),
        "indexDatasourceParamsField"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"datasourceParamsField"]),
        "indexAction"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"actionDefinition"]),
        "indexActionField"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"actionDefinitionField"]),
        "validateFormsField"=>array("TYPE" => "PAGE", "PAGE" => "index","PARAMS"=>["type"=>"validateFormField"]),

        /*

            case "pageDefinition":{}break;
            case "pageDefinitionField":{}break;
            case "typeDefinition":{}break;
            case "other":{}break;
            case "forms":{}break;
            case "datasources":{}break;
            case "actions":{}break;
            case "pages":{}break;*/
    );
}
