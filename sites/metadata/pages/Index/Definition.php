<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:22
 */

namespace sites\metadata\pages\Index;

include_once(PROJECTPATH."/model/web/objects/Page/PageDefinition.php");
class Definition extends \model\web\Page\PageDefinition
{
    function getPageDefinition()
    {
        return array(
            'CACHING'=>array('TYPE'=>'NO-CACHE'),
            'ENCODING'=>'utf8',
            'PERMISSIONS'=>array('PUBLIC'),
            'SOURCES'=>array(
            ),
            'FIELDS'=>array(
                "type"=>["TYPE"=>"String"],
                "modelName"=>["TYPE"=>"String"],
                "fieldName"=>["TYPE"=>"String"],
                "formName"=>["TYPE"=>"String"],
                "datasourceName"=>["TYPE"=>"String"],
                "actionName"=>["TYPE"=>"String"],
                "fieldValue"=>["TYPE"=>"PHPVariable"],
                "fieldPath"=>["TYPE"=>"String"]
            )
        );
    }
}
