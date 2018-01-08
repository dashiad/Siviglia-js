<?php

class Startup
{
    function __construct()
    {
        if(defined("DEVELOPMENT"))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
            ini_set("html_errors",1);
            ini_set("display_errors",1);
            ini_set("xdebug.max_nesting_level",500);
        }
    }
    static function initializeHTTPPage()
    {
        //session_start();
        include_once(LIBPATH."/Request.php");
        $request=Request::getInstance();
        global $currentSite;
        $currentSite=\model\web\Site::getCurrentWebsite();
        Registry::addService("site",$currentSite);
        
    }

    static function commonSetup()
    {
        include_once(LIBPATH."/autoloader.php");
        include_once(LIBPATH."/reflection/model/ObjectDefinition.php");
        include_once(LIBPATH."/model/BaseException.php");
        include_once(LIBPATH."/model/types/BaseType.php");
        include_once(LIBPATH."/Registry.php");
        include_once(LIBPATH."/datasource/DataSourceFactory.php");
        include_once(LIBPATH."/model/BaseTypedObject.php");
        include_once(PROJECTPATH."/lib/model/permissions/AclManager.php");
        include_once(PROJECTPATH."/vendor/autoload.php");
        $ser=\lib\storage\StorageFactory::getSerializerByName("default");
        $oPerms=new \lib\model\permissions\AclManager($ser);
        \Registry::addService("permissions",$oPerms);




        //Incluimos las constantes en el registro para poder ser usadas en DS
        $constants = get_defined_constants(true);
        foreach($constants["user"] as $constant=>$constantValue) {
            Registry::store($constant, $constantValue);
        }
        Startup::initializeContext();
        //Startup::initializeSerializers();

        //   date_default_timezone_set($confClass::$DEFAULT_TIMEZONE);
    }
    static function initializeContext()
    {

        global $globalContext;
        global $globalPath;
        $globalContext=new \lib\model\SimpleContext();
        $globalPath=new \lib\model\SimplePathObject();
        $globalPath->addPath("registry",Registry::$registry);
    }
    static function initializeUser()
    {
        // Se obtiene el usuario actual
       /* global $oCurrentUser;
        $oCurrentUser=$currentProject->getUserFactory()->getUser($request);
        \Registry::store(\Registry::USER,$oCurrentUser);
        \Registry::store(\Registry::USER_LANGUAGE_ISO,$oCurrentUser->getEffectiveLanguage());*/
        // Finalmente, se enruta la request

    }
    static function initializeSerializers()
    {
        global $SERIALIZERS;
        \lib\storage\StorageFactory::$defaultSerializers=$SERIALIZERS;
        \lib\storage\StorageFactory::$defaultSerializer="default";
    }
}
Startup::commonSetup();



function io($arr,$key,$alt)
{
    return isset($arr[$key])?$arr[$key]:$alt;
}



// Funciones definidas aqui para simplificar el codigo de obtencion de modelos.
$modelCache=array();

function getModel($objName,$fields=null)
{

    return \lib\model\ModelCache::getInstance($objName,$fields);
}

function getModelInstance($objName,$serializer=null,$definition=null)
{
    return \lib\model\BaseModel::getModelInstance($objName,$serializer,$definition);
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
register_shutdown_function('___cleanup');

function breakme()
{
    $a=1;
    $q=10;
}
function debug($str,$die=false)
{
    echo $str;
    if($die)
        die();
}



