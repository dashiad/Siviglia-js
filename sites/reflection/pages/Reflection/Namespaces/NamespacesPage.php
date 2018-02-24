<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 26/11/2017
 * Time: 19:14
 */

namespace sites\reflection\pages\Reflection\Namespaces;

class NamespacesPage extends \model\web\Page
{
    var $models;
    function initializePage($params)
    {

    }
    function getModels()
    {
        return $this->models;
    }
}