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

abstract class PageDefinition extends \lib\model\BaseTypedObject
{
    private $pageInstance;

    function __construct()
    {
        $pageDefinition=$this->getPageDefinition();
        if (!isset($pageDefinition["FIELDS"]))
                $pageDefinition["FIELDS"] = array();


        if (isset($pageDefinition["ROLE"]) &&
            in_array($pageDefinition["ROLE"],array(\model\web\Page::ROLE_ADD,\model\web\Page::ROLE_EDIT,\model\web\Page::ROLE_DELETE)))
        {
            if (!is_array(\Registry::$registry["action"])) {
                \Registry::$registry["action"] = array("FIELDS" => \Registry::$registry["params"]);
            } else {
                foreach (\Registry::$registry["params"] as $key => $value)
                    \Registry::$registry["action"]["FIELDS"][$key] = $value->getValue();
                //\Registry::$registry["action"]["FIELDS"]=array_merge(\Registry::$registry["action"]["FIELDS"],\Registry::$registry["params"]);
            }
        }
        $getData = \Registry::$registry["params"];
        $fullData = $getData;

        $this->initializeDefinition($pageDefinition);
        \lib\model\BaseTypedObject::__construct($pageDefinition);

        foreach ($pageDefinition["FIELDS"] as $getKey => $getDef) {
            if (!isset($fullData[$getKey])) {
                if ($getDef["REQUIRED"]) {

                    throw new PageDefinitionException(PageDefinitionException::ERR_REQUIRED_PARAM, array("name" => $getKey));
                } else
                    unset($pageDefinition["FIELDS"][$getKey]);
            } else {

                if (is_object($fullData[$getKey])) {
                    $curVal = $fullData[$getKey]->getValue();
                } else
                    $curVal = $fullData[$getKey];

                \lib\model\types\TypeFactory::unserializeType($this->{"*" . $getKey}, $curVal, "HTML");
            }
        }
        $instance = null;
        if (isset($pageDefinition["MODELIDS"])) {

            foreach ($pageDefinition["MODELIDS"] as $key => $value) {
                $modelName = $key;
                $curKey = array();
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

        if ($instance) {
            global $oCurrentUser;
            try {
                $this->checkPermissions();

            } catch (\Exception $e) {
                echo "Sin permisos";
                exit();
            }
        }

    }

    private function initializeDefinition(& $pageDefinition)
    {
        $addFields=array();
        $role=io($pageDefinition,"ROLE","");
        switch($role)
        {
            case \model\web\Page::ROLE_VIEW:
            {

            }break;
            case \model\web\Page::ROLE_LIST:
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
            case \model\web\Page::ROLE_ADD:
            {

            }break;
            case \model\web\Page::ROLE_EDIT:
            {

            }break;
            case \model\web\Page::ROLE_DELETE:
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

    private function checkPermissions()
    {
        if(isset($this->definition["PERMISSIONS"]))
        {
            $d=$this->definition["PERMISSIONS"];
            $permission=$d["PERMISSION"];
            $model=$d["TARGET_MODEL"];
            $sourceModel=io($d,"SOURCE_MODEL",$model);
            $sourceId=io($d,"KEY_PARAM",null);
            $target=array("GROUP"=>$sourceModel);
            if($sourceId!=null)
                $target["ITEM"]=$this->pageInstance[$sourceId]->getValue();

            global $SERIALIZERS;
            $ser=\lib\storage\StorageFactory::getSerializer($SERIALIZERS["web"]);
            include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
            $oPerms=new \AclManager($ser);

            // $permissionsChecking[]=array(array(array("GROUP"=>"Documents","ITEM"=>"create"),array("GROUP"=>"Workers","ITEM"=>"Alberto"),array("GROUP"=>"Documents","ITEM"=>"salaries")),true); //0
            global $oCurrentUser;
            if(!$oPerms->acl_check(array("GROUP"=>$model,"ITEM"=>$permission),array("ITEM"=>$oCurrentUser->getId()),$target))
            {
                throw new PageDefinitionException(PageDefinitionException::ERR_NO_PERMISSIONS);
            }
        }
    }


    /*    function checkPermissions($user, $modelInstance)
        {
            $permManager = \Registry::getPermissionsManager();
            $perms = $this->definition["PERMISSIONS"];

            if (!$permManager->canAccessModel($modelInstance, $perms, $user ? $user->getId() : null))
                throw new WebPageException(WebPageException::ERR_UNAUTHORIZED);
        }*/
}
