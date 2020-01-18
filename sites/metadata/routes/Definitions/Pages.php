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
        /*
         case "formDefinition":{}break;
case "formDefinitionField":{}break;
            case "modelDefinition":{}break;
            case "modelDefinitionField":{}break;
            case "datasourceDefinition":{}break;
            case "datasourceDefinitionField":{}break;
            case "actionDefinition":{}break;
            case "actionDefinitionField":{}break;
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
