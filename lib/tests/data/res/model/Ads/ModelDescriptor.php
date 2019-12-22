<?php
/**
 * Class SimpleModelDescriptor
 * @package lib\tests\model\stubs
 *  (c) Smartclip
 */


namespace lib\tests\data\res\model\Ads;


class ModelDescriptor extends \lib\model\ModelDescriptor
{
    function __construct($name,$package)
    {
        parent::__construct($name,null,$package);
    }
    function getDestinationFile($extraPath = null)
    {

        return $this->baseDir . "/model/" . $this->layer . "/".$this->namespaceClassName ."/". $this->className . "/" . ($extraPath ? $extraPath : "");

    }

}
