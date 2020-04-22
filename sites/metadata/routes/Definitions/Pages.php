<?php

namespace sites\metadata\routes\Definitions;


class Pages
{
    static $definition = array(
        "indexForms" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]],"PAGE" => "index","PARAMS"=>["type"=>"formDefinition"]),
        "indexFormsField" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"formDefinitionField"]),
        "indexModel" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"modelDefinition"]),
        "indexModelField" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"modelDefinitionField"]),
        "indexDatasource"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"datasourceDefinition"]),
        "indexDatasourceField"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"datasourceDefinitionField"]),
        "indexDatasourceParams"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"datasourceParams"]),
        "indexDatasourceParamsField"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"datasourceParamsField"]),
        "indexAction"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"actionDefinition"]),
        "indexActionField"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"actionDefinitionField"]),
        "indexTypeJs"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]], "PAGE" => "index","PARAMS"=>["type"=>"typeJs"]),
        "validateFormsField"=>array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["validation"=>["ROLE"=>"method","NAME"=>"validate"]], "PAGE" => "index","PARAMS"=>["type"=>"validateFormField"]),
        // Listado Forms y Datasources
        "listForms" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]],"PAGE" => "index","PARAMS"=>["type"=>"forms"]),
        "listDatasources" => array("TYPE" => "PAGE","RESPONSE"=>["TYPE"=>"JSON"],"SOURCES"=>["definition"=>["ROLE"=>"method","NAME"=>"getMetaDefinition"]],"PAGE" => "index","PARAMS"=>["type"=>"datasources"])


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
