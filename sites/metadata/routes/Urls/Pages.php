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
        "/model/{*modelName}/forms/{formName}/field/{fieldName}" => "indexFormsField"
    );
}
