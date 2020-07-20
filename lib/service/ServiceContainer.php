<?php
/**
 * Class ServiceContainer
 * @package lib\service
 *  (c) Smartclip
 */


namespace lib\service;

class ServiceContainerException extends \lib\model\BaseException
{
    const ERR_UNKNOWN_SERVICE=100;
    const TXT_UNKNOWN_SERVICE="Servicio desconocido:[%service%]";
}

class ServiceContainer
{
    var $services;
    function addService($name,$service)
    {
        $this->services[$name]=$service;
    }
    function getServiceByName($service)
    {
        if(!isset($this->services[$service]))
            throw new ServiceContainerException(ServiceContainerException::ERR_UNKNOWN_SERVICE,["service"=>$service]);
        return $this->services[$service];
    }
}
global $ServiceContainer;
$ServiceContainer=new ServiceContainer();

