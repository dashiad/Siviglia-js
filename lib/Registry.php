<?php
class RegistryException extends \lib\model\BaseException
{
    const ERR_NO_SUCH_SERVICE=1;
    const TXT_NO_SUCH_SERVICE="El servicio [%serviceName%] no existe.";
}

class Registry
{
    public static $registry = null;
    public static $saved=false;
    const SERVICE_CONTAINER="ServiceContainer";

    function __construct($requestFormat = "html")
    {
        Registry::$registry = array();
    }
    static function initialize(Request $request=null)
    {
        Registry::$registry["site"] = $request->getCurrentSite();
        Registry::$registry["params"]  = $request->getParameters();
        Registry::$registry["request"] = & $request;
        Registry::$registry["client"]  = $request->getClientData();
        Registry::$registry["action"]  = $request->getActionData();
        $session=Registry::getService("session");


        global $oCurrentUser;
        $userId=$session->retrieve("Registry/userId");
        $oCurrentUser=new \model\web\WebUser();
        if ($userId)
        {
            $oCurrentUser->setLogged($userId);
        }
        Registry::$registry["user"] = $oCurrentUser;
        Registry::$registry[Registry::SERVICE_CONTAINER]->addService("user",$oCurrentUser);



        \Registry::$registry["session"] = $session->getId();
        \Registry::$registry["cookies"] = & $_COOKIE;

        if (isset($session["Registry/request_date"]))
            \Registry::$registry["client"]["lastRequestDate"] = $session["Registry/request_date"];
        $session["Registry/request_date"] = time();

        // Se cargan los estados actuales
        if(isset($session["Registry/states"])) {
            \Registry::$registry["states"] = $session["Registry/states"];
        }

        if (isset($session["Registry/lastAction"]))
        {
            $action = new \lib\action\ActionResult();
            $action->unserialize($session["Registry/lastAction"]);
            \Registry::$registry["lastAction"] = $action;
        }

        if (isset($session["Registry/lastForm"]))
            \Registry::$registry["lastForm"] = $session["Registry/lastForm"];

    }

    static function store($key,$value)
    {
        Registry::$registry[$key]=$value;
    }

    static function getRequest()
    {
        return Registry::$registry["request"];
    }
    static function save()
    {
        if(Registry::$saved)
            return;
        Registry::$saved=true;
        $session=Registry::getService("session");
        $session["Registry/SESSION"] = Registry::$registry["session"];

        if (isset(Registry::$registry["newForm"]))
        {
            // Si existian ficheros, hay que eliminarlos de lastAction, ya que no se pueden resetear.
            if(isset($_FILES) &&  !empty($_FILES))
            {
                foreach(Registry::$registry["newForm"]["DATA"] as $key=>$value)
                {
                    if(isset($_FILES[$key]))
                    {
                        unset(Registry::$registry["newForm"]["DATA"][$key]);
                    }
                }
            }
            $session["Registry/lastForm"] = Registry::$registry["newForm"];
        }
        else
            unset($session["Registry/lastForm"]);

        if (isset(Registry::$registry["newAction"]))
        {

            $lastAction  = Registry::$registry["newAction"]->serialize();

            $session["Registry/lastAction"]=$lastAction;
        }
        else
            unset($session["Registry/lastAction"]);

        global $oCurrentUser;

        unset($session["Registry/userId"]);
        if (isset($oCurrentUser))
        {
            if ($oCurrentUser->isLogged())
            {
                $session["Registry/userId"] = $oCurrentUser->getId();

            }
        }
    }
    static  $services=array();
    static function addService($serviceName,$instance)
    {
        Registry::$registry[Registry::SERVICE_CONTAINER]->addService($serviceName,$instance);
    }
    static function getService($serviceName)
    {
        return Registry::$registry[Registry::SERVICE_CONTAINER]->getServiceByName($serviceName);
    }
}

?>
