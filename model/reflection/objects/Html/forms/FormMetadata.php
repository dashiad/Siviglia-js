<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:32
 */

namespace model\reflection\Html\forms;
include_once(PROJECTPATH."/model/reflection/objects/base/BaseMetadata");


class FormMetaData extends \model\reflection\base\BaseMetadata {
    function __construct($objName,$formName)
    {

        $Obj=new \model\reflection\Model\ModelName($objName);
        $basePath=$Obj->getDestinationFile("/html/forms/$formName.php");
        $fileName=realpath($basePath);
        if(strstr($basePath,PROJECTPATH)===false || !is_file($basePath))
        {
            echo "{}";exit();
        }

        include_once($basePath);
        $className=$Obj->getNamespaced().'\html\forms\\'.$formName;
        $inst=new $className();

        $definition=$inst->getDefinition();
        if(isset($definition["INHERIT"]))
        {
            $actName=$definition["INHERIT"]["ACTION"];
            include_once($Obj->getActionFileName($actName));
            $actName=$Obj->getNamespacedAction($actName);
            $actInstance=new $actName();
            $actDef=$actInstance->getDefinition();
            $definition["FIELDS"]=$actDef["FIELDS"];
        }

        $fDef=$definition["FIELDS"];
        foreach($fDef as $key=>$value)
        {
            $definition["FIELDS"][$key]=array_merge($value,\lib\model\types\TypeFactory::getTypeMeta($value));
        }
        $this->definition=$definition;

    }
}