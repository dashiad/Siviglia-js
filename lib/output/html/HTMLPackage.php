<?php
/**
 * Class HTMLPackage
 * @package lib\output\html
 *  (c) Smartclip
 */


namespace lib\output\html;


class HTMLPackage extends \lib\model\Package
{
    function __construct()
    {
        parent::__construct("/sites",PROJECTPATH);
    }

    function getModelDescriptor($objectName)
    {
        return new \lib\model\ModelDescriptor($objectName,null,$this);
    }
}
