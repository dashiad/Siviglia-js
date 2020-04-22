<?php
namespace lib\action;

class ActionException extends \lib\model\BaseException
{
    const ERR_CANT_EDIT_WITHOUT_KEY=1;
    const ERR_REQUIRED_FIELD=2;
}

class Action extends \lib\model\BaseTypedObject
{
    protected $destModel = null;
    protected $actionResult=null;

    function __construct($definition)
    {

        if(!$definition)
        {
            $n=get_class($this);
            parent::__construct($n::$definition);
        }
        else
            parent::__construct($definition);
    }

    static function getAction($object, $name)
    {
        $objName = \lib\model\ModelService::getModelDescriptor($object);
        include_once($objName->getActionFileName($name));
        $className = $objName->getNamespacedAction($name);
        $instance = new $className();
        return $instance;
    }

    function setModel($model)
    {
        $this->destModel = $model;
    }

    // En este punto, la validacion de requisitos de campos, etc, ya esta hecha.

    function process($fields, & $actionResult, $user)
    {
        $this->actionResult=$actionResult;

	    $def=$this->getDefinition();
	    $keys=null;
	    if(isset($def["INDEXFIELDS"]))
        {
            $keys=[];
            for($k=0;$k<count($def["INDEXFIELDS"]);$k++)
            {
                $i=$def["INDEXFIELDS"][$k];
                $keys[$i]=$fields->{$i};
                if($keys[$i]==null) {
                    $actionResult->addGlobalError(new ActionException(ActionException::ERR_CANT_EDIT_WITHOUT_KEY));
                    return false;
                }

            }
        }
        $this->__validateArray($fields,$actionResult);
	    if(!$actionResult->isOk())
        {
            return $this->onError(null, $fields, $actionResult, $user);
        }

	    $this->loadFromArray($fields,true,false,$actionResult);


	    if($actionResult->isOk()) {
            $this->__loaded=true;
            $this->validate($actionResult);
        }

	    if ($actionResult->isOk()) {
	        $unserializing=false;
	        if (!$this->destModel)
	        {
	            // Se carga el modelo, y se asignan campos.
                if ($def["MODEL"]) {
                    $s=\Registry::getService("model");
                    $this->destModel = $s->getModel($def["MODEL"]);
                    }
                else
                {
                    $this->destModel=$this;
                    $unserializing=true;
                }

	        }


            // En este momento, ya se tiene el modelo.Ahora ya es posible comprobar permisos.
            if ($def["PERMISSIONS"]) {
                /* $permissionDef=new \lib\reflection\classes\PermissionRequirementsDefinition($def["PERMISSIONS"]);
                 $requirements=$permissionDef->getRequiredPermissions($fields);
                 include_once(PROJECTPATH."/lib/model/permissions/PermissionsManager.php");
                 $oPerms=new \PermissionsManager($ser);
                 $canAccess=$oPerms->canAccess($fields,$requirements,$user);
                 if(!$canAccess)
                 {
                    // Error, no existen permisos para acceder a este objeto.
                    $actionResult->addPermissionError();
                 }*/
            }
            if($actionResult->isOk()) {
                switch($def["ROLE"])
                {
                    case "Delete":
                    {
                        $this->destModel->delete();
                        $actionResult->setModel(null);
                    }break;
                    case "Static":
                    {
                        $actionResult->setModel(null);
                    }break;
                    case "SEARCH":
                    {
                        $actionResult->setModel(null);
                    }break;
                    default:
                    {
                        $val=$this->__transferFields($def["MODEL"]);
                        $this->destModel->loadFromArray($val, false, false, $actionResult);
                        $actionResult->setModel($this->destModel);
                        if($actionResult->isOk())
                            $this->onSaved($this->destModel);
                    }
                }
            }
	    }
        if (!$actionResult->isOk())
            return $this->onError($keys, $fields, $actionResult, $user);
        else {
            $this->onSuccess($actionResult->getModel(),null);
        }

    }

    function onError($keys, $params, $actionResult, $user)
    {
        return true;
    }

    function onSuccess($model, $user)
    {
        return true;
    }

    function onSaved($model)
    {

    }

    function getModel()
    {
        return $this->model;
    }

    function getParametersInstance()
    {
        $definition=$this->getDefinition();
        return new \lib\model\BaseTypedObject($definition);
    }
}
