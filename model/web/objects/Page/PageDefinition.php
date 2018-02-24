<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:49
 */

namespace model\web\Page;
class PageDefinitionException extends \lib\model\BaseException
{
    const ERR_DEFINITION_NOT_INITIALIZED=1;
    const ERR_NO_PERMISSIONS=2;
    const ERR_REQUIRED_PARAM=3;
    const TXT_DEFINITION_NOT_INITIALIZED="Definition not initialized";
    const TXT_NO_PERMISSIONS="Not enough permissions";
    const TXT_REQUIRED_PARAM="Required parameter";
}

abstract class PageDefinition extends \lib\model\BaseDefinition
{
    private $pageInstance;
    private $__modelInstance;
    function __construct()
    {
        $pageDefinition=$this->getPageDefinition();
        if (!isset($pageDefinition["FIELDS"]))
                $pageDefinition["FIELDS"] = array();

        if (isset($pageDefinition["ROLE"]) &&
            in_array($pageDefinition["ROLE"],array(\model\web\Page::PAGE_ROLE_CREATE,\model\web\Page::PAGE_ROLE_EDIT)))
        {
            if (!is_array(\Registry::$registry["action"])) {
                \Registry::$registry["action"] = array("FIELDS" => \Registry::$registry["params"]);
            } else {
                foreach (\Registry::$registry["params"] as $key => $value)
                    \Registry::$registry["action"]["FIELDS"][$key] = $value->getValue();
                //\Registry::$registry["action"]["FIELDS"]=array_merge(\Registry::$registry["action"]["FIELDS"],\Registry::$registry["params"]);
            }
        }
        $this->initializeDefinition($pageDefinition);
        \lib\model\BaseTypedObject::__construct($pageDefinition);


    }

    private function initializeDefinition(& $pageDefinition)
    {
        $addFields=array();
        $role=io($pageDefinition,"ROLE","");
        switch($role)
        {
            case \model\web\Page::PAGE_ROLE_VIEW:
            {
            }break;
            case \model\web\Page::PAGE_ROLE_LIST:
            {
                $addFields=array(
                    '__start'=>array('TYPE'=>'Integer'),
                    '__count'=>array('TYPE'=>'Integer'),
                    '__sort'=>array(
                        'TYPE'=>'String',
                        'MAXLENGTH'=>30
                    ),
                    '__sortDir'=>array(
                        'TYPE'=>'Enum',
                        'VALUES'=>array('ASC','DESC'),
                        'DEFAULT'=>'ASC'
                    ),
                    '__sort1'=>array(
                        'TYPE'=>'String',
                        'MAXLENGTH'=>30
                    ),
                    '__sortDir1'=>array(
                        'TYPE'=>'Enum',
                        'VALUES'=>array('ASC','DESC'),
                        'DEFAULT'=>'ASC'
                    )
                );
            }break;
            case \model\web\Page::PAGE_ROLE_CREATE:
            {
            }break;
            case \model\web\Page::PAGE_ROLE_EDIT:
            {
            }break;
        }
        foreach($addFields as $key=>$value)
        {
            $pageDefinition["FIELDS"][$key]=$value;
        }

        return $pageDefinition;
    }
    abstract function getPageDefinition();

    function getRequiredPermissions()
    {
        return io($this->getPageDefinition(),"PERMISSIONS",null);
    }

    function createPageModelInstance($pageDefinition)
    {
        $this->__modelInstance=null;
        if(! in_array($this->role,array(\model\web\Page::PAGE_ROLE_VIEW, \model\web\Page::PAGE_ROLE_CREATE,\model\web\Page::PAGE_ROLE_EDIT)))
            return;
        $instance=null;
        if (isset($pageDefinition["MODELIDS"])) {
            foreach ($pageDefinition["MODELIDS"] as $key => $value) {
                $modelName = $key;
                $instance = \lib\model\BaseModel::getModelInstance($modelName);
                $instance->disableStateChecks();
                foreach ($value as $fieldKey) {
                    $curField = $this->__getField($fieldKey);
                    $instance->{$fieldKey} = $curField->getType()->getValue();
                }
                try {
                    $instance->loadFromFields();
                    $instance->enableStateChecks();
                    \Registry::$registry["currentModel"] = $instance;
                } catch (\lib\model\BaseModelException $e) {
                }
            }

        } else {
            if (isset($pageDefinition["MODEL"]))
                $instance = \lib\model\BaseModel::getModelInstance($pageDefinition["MODEL"]);
        }
        $this->__modelInstance=$instance;
        return $instance;
    }

    function getModelInstance()
    {
        return $this->__modelInstance==null?\Registry::getService("site"):$this->__modelInstance;
    }
    function getPermissionsTarget()
    {
        return $this->getModelInstance();
    }


}
