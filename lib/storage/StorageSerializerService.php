<?php
/**
 * Class TypeSerializerService
 * @package platform\storage
 *  (c) Smartclip
 */


namespace lib\storage;


use lib\model\types\BaseTypeException;
use lib\model\types\TypeFactory;


class StorageSerializerServiceException extends \lib\model\BaseException
{
    const ERR_SERIALIZER_NOT_FOUND=101;
    const TXT_SERIALIZER_NOT_FOUND="No encotrado serializador [%serializer%]";
    const ERR_SERIALIZER_CONFIG_NOT_FOUND=102;
    const TXT_SERIALIZER_CONFIG_NOT_FOUND="No encotrado configuracion [%config%] para el serializador [%serializer%]";

}

class StorageSerializerService
{
    var $config;
    var $serializers;
    var $defaultSerializer;
    function init($config)
    {
        $this->config=$config;
    }
    function setDefaultSerializer($definition)
    {
        $ser=$this->addSerializer($definition);
        $this->defaultSerializer=$ser;
        return $ser;
    }

    function addSerializer($name,$definition)
    {
        $this->config["serializers"][$name]=$definition;
    }

    function getDefaultSerializer($objName=null)
    {

        if(!$objName)
            return $this->getSerializerByName($this->config["default"]);
        else
        {
            $objNameClass=\lib\model\ModelService::getModelDescriptor($objName);
            $Cserializer=$objNameClass->getDefaultSerializer();
            if($Cserializer)
                return $this->getSerializerByName($Cserializer);
            else
                return $this->getSerializerByName($this->config["default"]);
        }
    }

    function getSerializerByName($name,$useDataSpace=true)
    {
        if(isset($this->serializers[$name]))
            return $this->serializers[$name];

        if(!isset($this->config["serializers"][$name]))
            throw new StorageSerializerServiceException(StorageSerializerServiceException::ERR_SERIALIZER_NOT_FOUND,array("name"=>$name));

        $parts=explode("::",$name);
        $baseSerializer=$parts[0];
        // Si el nombre del serializador contiene "::", se supone que lo que hay a la derecha de los "::", es un nombre
        // de clase que debe configurar el serializador.
        if(isset($parts[1]))
        {
            try{
                $cl=new $parts[1]();
            }
            catch(\Exception $e)
            {
                throw new StorageSerializerServiceException(StorageSerializerServiceException::ERR_SERIALIZER_CONFIG_NOT_FOUND,array("name"=>$name,"config"=>$parts[1]));
            }
        }


        $this->serializers[$name]=$this->getSerializerInstance($this->config["serializers"][$name],$useDataSpace);
        $this->serializers[$name]->setName($name);
        return $this->serializers[$name];
    }

    function getSerializer($definition=null,$useDataSpace=true)
    {
        return $this->getSerializerInstance($definition,$useDataSpace);
    }
    function getSerializerInstance($definition=null,$useDataSpace=true)
    {
        if($definition===null)
            return $this->getDefaultSerializer();

        $name=$definition["NAME"];
        if($name && isset($this->serializers[$name]))
            return $this->serializers[$name];
        //if(!isset($definition["ADDRESS"]))
         //   throw new StorageSerializerServiceException(StorageSerializerServiceException::ERR_SERIALIZER_NOT_FOUND,array("name"=>$name));
        if(isset($definition["MODEL"])) {
            $baseNamespace = $definition["MODEL"];
            $class=$definition["CLASS"];
            $modelService=\Registry::getService("model");
            $descriptor=$modelService->getModelDescriptor($baseNamespace);
            $serializer=$descriptor->getSerializer($class,isset($definition["PARAMS"])?$definition["PARAMS"]:[]);
        }
        else {
            $baseNamespace = '\lib\storage';
            $type = $definition["TYPE"];
            $serClass = $baseNamespace . '\\' . $type . '\\' . $type . "Serializer";
            $serializer = new $serClass($definition, $useDataSpace);
        }
        if($name)
            $this->serializers[$name]=$serializer;
        return $serializer;
    }


    function serializeType($mixedType,$serializer)
    {

        $typeSerializer=$this->getTypeSerializer($mixedType,$serializer);
        return $typeSerializer->serialize($mixedType);
    }
    function getTypeSerializer($mixedType,$serializer)
    {
        if(!is_object($serializer))
            $serializer=$this->getSerializerByName($serializer);
        $typeSerializer=$serializer->getTypeSerializer($mixedType);
        return $typeSerializer;
    }
}


