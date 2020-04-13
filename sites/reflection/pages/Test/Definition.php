<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:22
 */

namespace sites\reflection\pages\Test;

include_once(PROJECTPATH."/model/web/objects/Page/PageDefinition.php");
class Definition extends \model\web\Page\PageDefinition
{
    function getPageDefinition()
    {
        return array(
            'CACHING'=>array('TYPE'=>'NO-CACHE'),
            'ENCODING'=>'utf8',
            'PERMISSIONS'=>array(array("PERMISSION"=>\lib\model\permissions\PermissionsManager::PERMS_REFLECTION)),
            //'PERMISSIONS'=>array(["TYPE"=>"Public"]),
            'SOURCES'=>array(
            ),
            'FIELDS'=>array(
            )
        );
    }
}
