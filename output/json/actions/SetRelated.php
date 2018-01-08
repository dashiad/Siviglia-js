<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 23/07/13
 * Time: 11:54
 * To change this template use File | Settings | File Templates.
 */
namespace json\actions;
include_once(PROJECTPATH."/backoffice/lib/output/json/JsonAction.php");
class SetRelated extends \JsonAction
{
    function __construct($params)
    {
        parent::__construct(
            array(
                "object"=>"Payment",
                "action"=>"SetRelated"
            ),
            $params
        );
    }
}