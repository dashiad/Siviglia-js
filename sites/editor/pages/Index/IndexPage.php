<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 26/11/2017
 * Time: 19:14
 */

namespace sites\editor\pages\Index;

class IndexPage extends \model\web\Page
{
    function initializePage($params)
    {

        if(time()%2)
            $this->setTemplateParams(["MiWidget"=>"/TESTING_wids/WID1"]);
        else
            $this->setTemplateParams(["MiWidget"=>"/TESTING_wids/WID2"]);
        // TODO: Implement initializePage() method.
    }
}
