<?php
namespace lib\action;

class ActionResult extends \lib\model\ModelFieldErrorContainer
{
    var $inputErrors=array();
    var $permissionError=false;
    var $globalErrors=array();
    var $model=null;
    function reset()
    {
        parent::reset();
        $this->inputErrors=array();
        $this->globalErrors=array();
        $this->permissionError=false;
    }

    function addFieldInputError($field,$value,$exception)
    {
        $this->addFieldTypeError($field,$value,$exception);
        /*$this->isOk=false;
        $code=$exception->getCode();
        $this->inputErrors[$field][$exception->getCodeString()][$code]=array("input"=>$input,"value"=>$value,"code"=>$code);*/
    }

    function addGlobalError($exception)
    {
        $this->isOk=false;
        $code=$exception->getCode();
        $this->globalErrors[$exception->getCodeString()]=array(
            "code"=>$code,
            "params"=>$exception->getParams(),
            "str"=>$exception->__toString());
    }

    function addPermissionError()
    {
        $this->permissionError=true;
        $this->isOk=false;
    }
    function serialize()
    {
        return array($this->permissionError,$this->fieldErrors,$this->inputErrors,$this->globalErrors,$this->isOk);
    }
    function unserialize($data)
    {
        list($this->permissionError,$this->fieldErrors,$this->inputErrors,$this->globalErrors,$this->isOk)=$data;
    }

    function getGlobalErrors()
    {
        return $this->globalErrors;
    }
    function setModel($model)
    {
        $this->model=$model;
    }
    function getModel()
    {
        return $this->model;
    }

}
