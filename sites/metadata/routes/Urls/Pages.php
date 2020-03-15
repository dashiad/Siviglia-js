<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 27/07/15
 * Time: 19:22
 */

namespace sites\metadata\routes\Urls;


class Pages
{
    static $definition = array(
        "/model/{*modelName}/definition"=>"indexModel",
        "/model/{*modelName}/field/{fieldName}"=>"indexModelField",
        "/model/{*modelName}/forms/{formName}/definition" => "indexForms",
        "/model/{*modelName}/forms/{formName}/field/{fieldName}" => "indexFormsField",
        "/model/{*modelName}/datasources/{datasourceName}/definition" => "indexDatasource",
        "/model/{*modelName}/datasources/{datasourceName}/field/{fieldName}" => "indexDatasourceField",
        "/model/{*modelName}/datasources/{datasourceName}/params/definition" => "indexDatasourceParams",
        "/model/{*modelName}/datasources/{datasourceName}/params/{fieldName}" => "indexDatasourceParamsField",
        "/model/{*modelName}/actions/{actionName}/definition" => "indexAction",
        "/model/{*modelName}/actions/{actionName}/field/{fieldName}" => "indexActionField",
        "/js/types/{*fieldName}"=>"indexTypeJs",
        "/validate/model/{*modelName}/forms/{formName}/field/{fieldName}" => "validateFormsField",

    );
}
