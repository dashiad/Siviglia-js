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
    var $actionName;
    var $objectName;
    var $actionResult;
    var $form;
    function __construct($request)
    {

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
        $formInfo=\lib\output\html\Form::getFormPath($this->object,$this->actionName);
        $className=$formInfo["CLASS"];
        $classPath=$formInfo["PATH"];

        // Se incluye la definicion del formulario.
        include_once($classPath);
        $actionResult=new \lib\action\ActionResult();
        $this->form=new $className($actionResult);
        $this->form->resetResult();
        return $this->form;
    }
    function execute()
    {
        include_once(PROJECTPATH."/lib/action/Action.php");

        if($this->actionName=="" || $this->object=="")
            return false; // TODO : Redirigir a pagina de error.
        $curForm=$this->getForm();
        $this->actionResult=$curForm->process(false);
        return $this->actionResult;

    }
    function getActionResult()
    {
        return $this->actionResult;
    }
    abstract function resolve();
}