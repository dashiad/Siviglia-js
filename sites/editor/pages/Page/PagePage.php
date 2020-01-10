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
        $id_page=$this->id_page;
        echo "ID_PAGE::".$id_page;
    }

}
