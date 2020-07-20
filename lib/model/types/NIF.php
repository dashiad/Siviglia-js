<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 30/10/13
 * Time: 14:57
 * To change this template use File | Settings | File Templates.
 */

namespace lib\model\types;


class NIF extends _String{
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $definition['TYPE']='NIF';
        $definition['MINLENGTH']=9;
        $definition['MAXLENGTH']=9;
        _String::__construct($name,$def,$parentType,$value,$validationMode);
    }

    function _validate($val)
    {
        $val=strtoupper($val);
        if(!parent::validate($val))
            return false;
        if(substr("TRWAGMYFPDXBNJZSQVHLCKE",strtr(substr($val,0,-1),"XYZ","012")%23,1)!=substr($val,-1))
            throw new \lib\model\types\BaseTypeException(\lib\model\types\BaseTypeException::ERR_INVALID,null,$this);
        return true;
    }
}
