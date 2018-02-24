<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:14
 */

namespace model\reflection\base;


class BaseMetadata
{
    var $definition;
    function __construct($basePath, $namespaced, $variableName, $isStatic,$fieldResolution, $fieldHiding,$removeKeys,$definition=null)
    {
        if(strstr($basePath,PROJECTPATH)===false || !is_file($basePath))
        {
            $this->definition=array();
            return;
        }
        if($definition==null)
        {
            include_once($basePath);

            if($isStatic)
            {
                $inst = new $namespaced();
                $definition=$inst::$$variableName;
            }
            else
            {
                $inst=new $namespaced();
                $definition=$inst->getDefinition();
            }
        }

        $fDef=$definition["FIELDS"];
        foreach($fDef as $key=>$value)
        {
            $definition["FIELDS"][$key]=array_merge($value,\lib\model\types\TypeFactory::getTypeMeta($value));
        }


        if($fieldHiding)
        {
            foreach($fieldHiding as $value)
            {
                foreach($definition[$value] as $kk=>$vv)
                {
                    if($vv["PUBLIC_FIELD"]==false)
                        unset($definition[$value][$kk]);
                }
            }
        }

        if($removeKeys)
        {
            foreach($removeKeys as $value)
            {
                $start = & $definition;
                $parts=explode("/",$value);
                for($k=0;$k<count($parts)-1;$k++)
                    $start=& $start[$parts[$k]];
                unset($start[$parts[$k]]);
            }
        }
        $this->definition=$definition;
    }

}