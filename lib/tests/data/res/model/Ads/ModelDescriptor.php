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
   /* function getDestinationFile($extraPath = null)
    {
        if (!$this->isPrivate)
            return $this->baseDir . "/model/" . $this->layer . "/objects/" . $this->className . "/" . ($extraPath ? $extraPath : "");

        return $this->baseDir . "/model/" . $this->layer . "/objects/" . str_replace('\\', '/objects/', $this->namespaceClassName) . "/objects/" . $this->className . "/" . ($extraPath ? $extraPath : "");

    }*/

}
