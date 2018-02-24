<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 26/11/2017
 * Time: 19:14
 */

namespace sites\reflection\pages\Test;

class TestPage extends \model\web\Page
{
    function initializePage($params)
    {
        $factory=new \model\reflection\ReflectorFactory();
        $factory->loadFactory();

    }
}