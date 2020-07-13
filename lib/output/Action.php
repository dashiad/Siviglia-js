<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 23/10/2017
 * Time: 23:19
 */

namespace lib\output;


abstract class Action
{
    var $action;
    var $actionName;
    var $objectName;
    var $actionResult;
    var $request;
    var $form;

    function __construct($request,$mode)
    {
        $this->request=$request;
        $this->mode=$mode;
        if($this->mode==\lib\routing\Action::MODE_LOAD_FORM)
            $this->getForm();
    }
    function setObjectName($obj)
    {
        $this->objectName=$obj;
    }
    function setActionName($name)
    {
        $this->actionName=$name;
    }

    function getForm()
    {
        $formInfo=\lib\output\html\Form::getFormPath($this->objectName,$this->actionName);
        $className=$formInfo["CLASS"];
        $classPath=$formInfo["PATH"];

        // Se incluye la definicion del formulario.
        include_once($classPath);
        $this->form=\lib\output\html\Form::getForm($this->objectName,$this->actionName);
        $parameters=$this->request->getParameters();
        if($parameters)
        {
            $htmlSerializer=new \lib\storage\HTML\HTMLSerializer();
            $keyVals=[];
            $keyNames=$this->form->__getKeys()->getKeyNames();
            for($k=0;$k<count($keyNames);$k++)
            {
                if(isset($parameters[$keyNames[$k]])) {
                    $htmlSerializer->unserializeType($keyNames[$k], $this->form->{"*" . $keyNames[$k]}, $parameters, $this->form);
                    $keyVals[$keyNames[$k]]=$this->form->{$keyNames[$k]};
                }
            }

            $this->form->__setValidationMode(\lib\model\types\BaseType::VALIDATION_MODE_NONE);
            $this->form->load($keyVals);
            $this->form->__setValidationMode(\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE);
        }
        return $this->form;
    }
    function execute()
    {
        include_once(PROJECTPATH."/lib/action/Action.php");

        if($this->actionName=="" || $this->objectName=="")
            return false; // TODO : Redirigir a pagina de error.
        $curForm=$this->getForm();
        $this->actionResult=$curForm->process($this->request);
        return $this->actionResult;
    }
    function getActionResult()
    {
        return $this->actionResult;
    }
    abstract function resolve();
}