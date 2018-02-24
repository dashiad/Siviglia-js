<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 05/12/2017
 * Time: 0:22
 */

namespace sites\editor\pages\Site;


class SitePage extends \model\web\Page
{
    function initializePage($params)
    {
        $curSite=$params["namespace"];
    }

}