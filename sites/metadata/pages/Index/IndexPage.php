<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 18/11/2017
 * Time: 23:44
 */

namespace sites\metadata\pages\Index;


class IndexPage extends \model\web\Page
{
    function initializePage($params)
    {
        // TODO: Implement initializePage() method.
        echo $this->type;
    }
}
