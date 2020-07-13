<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:22
 */

namespace sites\editor\pages\Page;

include_once(PROJECTPATH."/model/web/objects/Page/PageDefinition.php");
class Definition extends \model\web\Page\PageDefinition
{
    function getPageDefinition()
    {
        return array(
            'CACHING'=>array('TYPE'=>'NO-CACHE'),
            'ENCODING'=>'utf8',
            'PERMISSIONS'=>array(["TYPE"=>"Public"]),
            'SOURCES'=>array(
            ),
            'FIELDS'=>array(
                'id_page'=>[
                    "MODEL"=>"/model/web/Page",
                    "FIELD"=>"id_page"
                ]
            )
        );
    }
}
