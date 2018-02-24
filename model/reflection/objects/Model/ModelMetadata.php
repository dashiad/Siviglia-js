<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:15
 */

namespace model\reflection\Model;
include_once(PROJECTPATH."/model/reflection/objects/base/BaseMetadata.php");

class ModelMetadata extends \model\reflection\base\BaseMetadata {
    function __construct($objName)
    {
        $Obj=new \model\reflection\model\ModelName($objName);
        $path=$Obj->getDestinationFile("Definition.php");
        parent::__construct($path, $Obj->getNamespaced().'\Definition', "definition", true ,array(), false,array('STORAGE'));
        $this->definition["layer"]=$Obj->layer;
        $this->definition["parentObject"]=null;
        $this->definition["name"]=$Obj->className;
        if($Obj->isPrivate())
        {
            $this->definition["private"]=1;
            $this->definition["parentObject"]=$Obj->getNamespaceModel();

        }
        if($this->definition["EXTENDS"])
        {
            $parentMeta=new ModelMetaData($this->definition["EXTENDS"]);
            $parentFields=$parentMeta->definition["FIELDS"];
            unset($this->definition["FIELDS"][$this->definition["INDEXFIELDS"][0]]);
            $this->definition["FIELDS"]=array_merge($this->definition["FIELDS"],$parentFields);

            $this->definition["ALIASES"]=array_merge($this->definition["ALIASES"]?$this->definition["ALIASES"]:array(),
                $parentMeta->definition["ALIASES"]?$parentMeta->definition["ALIASES"]:array());
        }
    }
}
