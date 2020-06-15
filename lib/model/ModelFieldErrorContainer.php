<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 15/02/2018
 * Time: 15:14
 */

namespace lib\model;


class ModelFieldErrorContainer
{
    var $fieldErrors=array();
    var $isOk=true;
    var $parsedFields=array();
    var $path=array();
    function reset()
    {
        $this->isOk=true;
        $this->fieldErrors=array();
    }
    function setCorrupted()
    {
        $this->isOk=false;
    }
    function addFieldTypeError($field,$value,$exception)
    {
        $this->isOk=false;
        $code=$exception->getCode();
  //      if(is_a($exception,'\lib\model\BaseTypedException'))
            $path=$field;
 //       else
 //           $path=$exception->getPath();
        $this->fieldErrors[$path][$exception->getCodeString()][$code]=array(
            "exception"=>$e,
            "value"=>$value,
            "code"=>$code,
            "path"=>"/".$path,
            "str"=>$exception->__toString()
        );
    }
    function pushPath($p)
    {
        $this->path[]=$p;
    }
    function popPath()
    {
        array_pop($this->path);
    }
    function isOk()
    {
        return $this->isOk;
    }
    function getFieldErrors($fieldName=null)
    {
        if($fieldName!=null)
            return io($this->fieldErrors,$fieldName,null);
        return $this->fieldErrors;
    }
    function addParsedField($field,$value)
    {
        $this->parsedFields[$field]=$value;
    }
    function getParsedFields()
    {
        return $this->parsedFields;
    }
}

?>


