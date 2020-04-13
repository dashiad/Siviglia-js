<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:22
 */

namespace sites\reflection\pages\Index;

include_once(PROJECTPATH."/model/web/objects/Page/PageDefinition.php");
class Definition extends \model\web\Page\PageDefinition
{
    function getPageDefinition()
    {
        return array(
            'CACHING'=>array('TYPE'=>'NO-CACHE'),
            'ENCODING'=>'utf8',
            'PERMISSIONS'=>array(array("TYPE"=>"Role","ROLE"=>"Reflection")),
            'SOURCES'=>array(
            ),
            'FIELDS'=>array(

            )
        );
    }
}
