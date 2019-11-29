<?php
/**
 * Class DefaultModelProvider
 * @package lib\model
 *  (c) Smartclip
 */


namespace lib\model;


class DefaultPackage extends \lib\model\Package
{

    function __construct()
    {
        parent::__construct("/model",PROJECTPATH);
    }

    function getModelDescriptor($objectName)
    {
        return new \lib\model\ModelDescriptor($objectName,null,$this);
    }

}
