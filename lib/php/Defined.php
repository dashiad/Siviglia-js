<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 28/08/15
 * Time: 13:09
 */

namespace lib\php;
use \lib\model\BaseException;

class Defined extends BaseException
{
    const ERR_REQUIRED_PARAMETER=1;
    const ERR_RELATIONSHIP_REQUIRES_ARRAY=2;
    const ERR_UNEXPECTED_CLASS=3;
    const ERR_UNKNOWN_PARAMETER=4;
    const ERR_CANT_CREATE_INTERFACE_FROM_ARRAY=5;
    const ERR_NULL_VALUE_AS_INSTANCE_VALUE=6;
}

class ArrayMappedParameters //implements \JsonSerializable
{

    static $defCache=array();
    var $definition;
    var $__doMap=false;
    function __construct($arr=null)
    {
        // Primero se crea la definicion completa.

        $called=get_called_class();
        if(!isset(ArrayMappedParameters::$defCache[$called]))
        {
            $def=$this::getDefinition();

            if(!isset($def["FIELDS"]))
                $def["FIELDS"]=array();
            ArrayMappedParameters::$defCache[$called]=$def;
        }

        if(is_object($arr) && get_class($arr)==get_class($this))
        {
            // No hay que hacer validacion, ya que el objeto anterior era valido.
            $arr->copyOver($this);
            return;
        }
        // Si MAP_TO_MEMBERS esta a 1, las keys de primer nivel de la definicion, se mapearan en variables miembro del objeto.

        $this->__doMap=false;
        if(isset($arr["MAP_TO_MEMBERS"]) && $arr["MAP_TO_MEMBERS"]==true)
            $this->__doMap=true;

        try{
            $this->checkParameters($arr);
            $this->definition=$arr;
        }
        catch(\Exception $e)
        {
            echo $e;
            throw $e;
        }

    }

    function checkParameters($arr)
    {

        $v=$this->asArray();
        $called=get_called_class();
        $definition=ArrayMappedParameters::$defCache[$called];
        foreach($v as $key=>$value)
        {
            $fieldDef=isset($definition["FIELDS"][$key])?$definition["FIELDS"][$key]:null;
            $curVal=isset($arr[$key])?$arr[$key]:(isset($value)?$value:null);
            if(!isset($curVal))
            {
                if(isset($fieldDef["DEFAULT"])) {
                    if($this->__doMap)
                        $this->{$key} = $fieldDef["DEFAULT"];
                    $arr[$key]=$fieldDef["DEFAULT"];
                }
                if(!isset($fieldDef["REQUIRED"]) || $fieldDef["REQUIRED"]==true)
                {
                    throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_REQUIRED_PARAMETER,
                        array("name"=>$key,"class"=>get_class($this)));
                }
            }
            if(!$fieldDef)
            {
                throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_UNKNOWN_PARAMETER,
                    array("name"=>$key,"class"=>get_class($this)));
            }

            if(isset($fieldDef["relation"]))
            {
                $this->checkRelation($key,$fieldDef,$arr);
            }
            else
            {
                if(isset($fieldDef["instanceof"]))
                    $this->checkInstance($key,$fieldDef,$arr);
                else
                {
                    // Tiene valor, tiene defincion, y no es ni relacion ni instancia.
                    $this->{$key}=$curVal;
                }
            }

        }
    }

    function checkRelation($fName,$definition,$values)
    {
        $isArr=is_array($values[$fName]);
        $isAssoc=false;
        if(!$isArr)
            $v=array($values[$fName]);
        else
            $v=$values[$fName];

        $keys=array_keys($values[$fName]);

        $finalVal=array();
        $required=isset($definition["required"])?$definition["required"]:true;
        for($k=0;$k<count($keys);$k++)
        {
            $nVal=$this->__getInstance($fName,$definition["relation"],$v[$keys[$k]],$isAssoc,$required);
            if($nVal!==null)
                $finalVal[$keys[$k]]=$nVal;
        }

        $this->{$fName}=$finalVal;
    }

    function checkInstance($fName,$definition,$values)
    {
        $this->{$fName}=$this->__getInstance($fName,$definition["instanceof"],$values[$fName],isset($definition["required"])?$definition["required"]:true);
    }

    function __getInstance($fName,$className,$value,$isRequired,$isAssoc=null)
    {
        if($value==null)
        {
            if($isRequired)
                throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_REQUIRED_PARAMETER,array("field"=>$fName,"class"=>$className));
            else
                return null;
        }
        if($isAssoc===true)
        {
            return new $className($value);
        }
        // Si un valor es un array, es porque el nombre de clase es una clase concreta.
        // Si no,
        if(is_array($value))
        {
            if(interface_exists($className))
            {
                // No nos pasan una clase, sino un interface.
                // Si existe esta key en el valor, lo tomamos como nombre de la clase concreta.
                if(!isset($value["__concreteClass__"]))
                    throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_CANT_CREATE_INTERFACE_FROM_ARRAY,array("interface"=>$className));
                    $className=$value["__concreteClass__"];
            }
            return new $className($value);
        }

        if(is_object($value))
        {
            $targetClass=\lib\autoload\AutoLoader::resolveAlias($className);
            if(is_a($value,$targetClass))
                return $value;
            throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_UNEXPECTED_CLASS,array("field"=>$fName,"expected"=>$targetClass,"got"=>get_class($value)));
        }
        throw new ArrayMappedParametersException(ArrayMappedParametersException::ERR_UNKNOWN_PARAMETER,array("field"=>$fName,"expected"=>$className,"got"=>$value));
    }


    static function getDefinition()
    {
        $current=get_called_class();
        $parent=get_parent_class($current);
        $cDef=isset($current::$__definition)?$current::$__definition:array();

        while($parent && $parent!=__CLASS__)
        {
            $d1=isset($parent::$__definition)?$parent::$__definition:null;
            if($d1)
                $cDef=\lib\php\ArrayTools::merge($d1,$cDef);
            $parent=get_parent_class($parent);
        }
        return $cDef;
    }

    function asArray()
    {
        $vars=get_object_vars($this);
        $result=array();
        foreach($vars as $key=>$value)
        {
            if($key[0]!=='_')
                $result[$key]=$value;
        }
        return $result;

    }
    function copyOver(ArrayMappedParameters $obj)
    {
        $vars=$this->asArray();
        foreach($vars as $key=>$value)
        {
            $obj->{$key}=$value;
        }
    }
    function jsonSerialize()
    {
        $result=array();
        $v=$this->asArray();
        $called=get_called_class();
        $definition=ArrayMappedParameters::$defCache[$called];
        foreach($v as $key=>$value)
        {
            if(!$value)
            {
                $result[$key]=null;
                continue;
            }

            $fieldDef=isset($definition["fields"][$key])?$definition["fields"][$key]:null;
            if(!$fieldDef)
            {
                $result[$key]=$value;
                continue;
            }
            if(isset($fieldDef["noSerialize"]))
                continue;
            if(isset($fieldDef["relation"]))
            {
                if(is_array($value))
                {
                    for($k=0;$k<count($value);$k++)
                    {
                        $result[$key][]=$value[$k]->jsonSerialize();
                    }

                }
                continue;
            }
            else
            {
                if(isset($fieldDef["instanceof"]))
                    $result[$key]=$value->jsonSerialize();
                else
                    $result[$key]=$value;
            }
        }
        return $result;
    }
}
