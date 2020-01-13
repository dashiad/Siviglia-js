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
    function getFormKeys($model,$form)
    {
        return array("id_page"=>$this->id_page);
    }
}
