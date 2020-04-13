<?php

use lib\model\Package;
use lib\model\ModelService;
include_once("autoloader.php");
include_once(LIBPATH."/model/BaseTypedObject.php");

class Startup
{
    static function init()
    {
        global $Container;


        if(defined("DEVELOPMENT"))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
            ini_set("html_errors",1);
            ini_set("display_errors",1);
            ini_set("xdebug.max_nesting_level",500);
        }
        include_once(LIBPATH."/autoloader.php");
        include_once(LIBPATH."/model/BaseException.php");
        include_once(LIBPATH."/model/types/BaseType.php");
        include_once(LIBPATH."/Registry.php");
        include_once(LIBPATH."/datasource/DataSourceFactory.php");
        include_once(LIBPATH."/model/BaseTypedObject.php");
        include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
        include_once(PROJECTPATH."/vendor/autoload.php");
        global $Config;

        $modelService=new ModelService();
        $modelService->initialize();
        $Container->addService("model",$modelService);
        $Container->addService("router",new \lib\Router());
        $storageService=new \lib\storage\StorageSerializerService();
        $storageService->addSerializer("PHP",[]);

        foreach($Config["SERIALIZERS"] as $key=>$value)
        {
            $storageService->addSerializer($key,$value);
        }
        $Container->addService("storage",$storageService);
        \Registry::$registry["ServiceContainer"]=$Container;

        include_once(LIBPATH."/Request.php");
        $request=Request::getInstance();
        Registry::initialize($request);
        Startup::commonSetup();

    }


    static function commonSetup()
    {

        Startup::initializeContext();
        $s=\Registry::getService("storage");
        $ser=$s->getSerializerByName("default");
        $oPerms = new \lib\model\permissions\PermissionsManager($ser);

        \Registry::addService("permissions",$oPerms);

        //Incluimos las constantes en el registro para poder ser usadas en DS
        /*$constants = get_defined_constants(true);
        foreach($constants["user"] as $constant=>$constantValue) {
            Registry::store($constant, $constantValue);
        }*/
        //Startup::initializeSerializers();

        //   date_default_timezone_set($confClass::$DEFAULT_TIMEZONE);
    }
    static function initializeContext()
    {
        $context=\lib\model\GlobalContext::getInstance();
        $context->user=\Registry::getService("user");
        $context->site=\model\web\Site::getCurrentWebsite();

        return;
        /*global $globalContext;
        global $globalPath;
        $globalContext=new \lib\model\SimpleContext();
        $globalPath=new \lib\model\SimplePathObject();
        $globalPath->addPath("registry",Registry::$registry);*/
    }

}



function io($arr,$key,$alt)
{
    return isset($arr[$key])?$arr[$key]:$alt;
}



// Funciones definidas aqui para simplificar el codigo de obtencion de modelos.
$modelCache=array();

function getModel($objName,$fields=null)
{
    $m=\Registry::getService("model")->getModel($objName);
    if($fields!==null)
    {
        foreach($fields as $k=>$v)
            $m->{$k}=$v;
        $m->loadFromFields();
    }
    return $m;
}

function getModelInstance($objName,$serializer=null,$definition=null)
{
    $s=\Registry::getService("model");
    return $s->getModel($objName,$serializer,$definition);
}

// La gestion de excepciones se realiza para evitar llamadas a file_exists,etc, que no ayudan al funcionamiento de apc_cache
function _load_exception_thrower($code, $string, $file, $line, $context)
{
    throw new Exception($string,$code);
}

function ___cleanup()
{
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    Registry::save();
    $session=\Registry::getService("session");
    $session->save();

    //   if($_GET["output"]!="json")
    //      dumpDebug();
    $last_error = error_get_last();
    if($last_error['type'] === E_ERROR || $last_error['type'] === E_USER_ERROR)
    {
        header("Content-type: text/html");
        echo "Error";
    }
    if (!( isset($_SERVER['argc']) && $_SERVER['argc']>=1 ))
    {
        \lib\php\FPMManager::getInstance()->runWorkers();
    }
}
include_once(LIBPATH."/service/ServiceContainer.php");
global $Container;
$Container=new \lib\service\ServiceContainer();
Startup::init();
// TODO : La siguiente linea deberia ir aqui, pero tiene una dependencia con el site,
// y la ejecucion por linea de comando no tiene site.Hay que resolver eso.
//Startup::commonSetup();
$curRequest=\Registry::getRequest();
$curRequest->solve();
