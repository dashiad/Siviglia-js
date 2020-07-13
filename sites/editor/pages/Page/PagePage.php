<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 05/12/2017
 * Time: 0:22
 */

namespace sites\editor\pages\Page;


class PagePage extends \model\web\Page
{
    function initializePage($params)
    {
    }
    function getFormModel($model,$form)
    {
        $s=\Registry::getService("model");
        $ins=$s->loadModel('\model\web\Page',["id_page"=>$this->id_page]);
        return $ins;
    }
}
