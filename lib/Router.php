<?php
namespace lib;
class RouterException extends \lib\model\BaseException
{
    const ERR_PAGE_NOT_FOUND=1;
    const ERR_CANT_FIND_ROUTE_DEFINITION=2;
}

class Router
{
    var $definitions;
    var $request;
    function __construct()
    {
        \Registry::addService("router",$this);
    }
    function route($request)
    {
        $this->request=$request;
        $className=get_class($request);
        switch($className)
        {
            case 'lib\output\html\HTMLRequest':
            {
                $this->routeHTML($request);
            }break;
        }
    }
    function resolveActions()
    {

        $object=\Registry::$registry["action"]["object"];
        $actionName=\Registry::$registry["action"]["name"];
        if($actionName=="" || $object=="")
            return; // TODO : Redirigir a pagina de error.

        $curForm=\lib\output\html\Form::getForm($object,$actionName,\Registry::$registry["action"]["keys"]);
        $curForm->process();
    }
    static function routeToReferer()
    {
        $request=\Registry::getRequest();
        $className=get_class($request);
        switch($className)
        {
            case 'lib\output\html\HTMLRequest':
            {
                header("Location: ".\Registry::$registry["client"]["referer"]);
            }break;
            default:
            {
                die();
            }
        }

    }


    function routeHTML($request)
    {
        $site = \model\web\Site::getCurrentWebsite();
        if($request->getRequestedPath()=="/action")
        {
            return $this->routeAction($request);
        }
        $cachePath=$site->getRouteCachePath();
        if (!is_file($cachePath)) {
            \lib\routing\RouteBuilder::rebuildSiteUrls($site);
        }
        $def = unserialize(file_get_contents($cachePath));
        $this->regexp = $def["REGEX"];
        $this->paths = $def["PATHS"];
        $this->definitions = $def["DEFINITIONS"];
        $this->resolve($request->getRequestedPath(),$request);
    }

    function routeAction($request)
    {
        switch($request->getOutputType())
        {
            case 'json':
            {
                $response=new \lib\output\json\JsonResponse();
                \Registry::$registry["response"]=$response;
                $action=new \lib\output\json\JsonAction($request);
                $response=new \lib\output\json\JsonResponse();
                $response->setBuilder(function() use ($action){
                    return $action->resolve();
                });
            }break;
            default:
            {
                $response=new \lib\Response();
                \Registry::$registry["response"]=$response;
                $response->setBuilder(function() use ($request){
                    return \lib\output\html\Form::resolve($request);
                });
            }break;
        }
    }

    /*
     * Genera una url dando el nombre de la url, y una lista de parametros.
     */
    var $revPaths=null;
    function generateUrl($name, $params)
    {
        if($this->revPaths==null)
            $this->revPaths=array_flip($this->paths);
        $definitions = $this->definitions;
        if (isset($definitions[$name])) {

            $f = function ($matches) use ($params) {
                if (is_array($params)) {
                    if (isset($params[$matches[1]]))
                        return $params[$matches[1]];
                } else {
                    // Se supone un objeto
                    return $params->{$matches[1]};
                }
            };
            return preg_replace_callback("/{([^}]*)}/", $f, $this->revPaths[$name]);
        }
    }

    function resolve($path,$request)
    {
        if (is_object($path)) {
            if (is_a($path, "Request")) {
                $this->request = $path;
                $fullPath = $this->request->getOriginalRequest();
            }
        } else
            $fullPath = $path;

        $matches = array();
        // Si el path no es "/", quitamos la "/" inicial

        $fullPath = urldecode($fullPath);
        $n = count($this->regexp);
        for ($k = 0; $k < $n && !($res = preg_match($this->regexp[$k], $fullPath, $matches1, PREG_OFFSET_CAPTURE)); $k++)
            ;
        if (!$res) {
            throw new RouterException(RouterException::ERR_PAGE_NOT_FOUND, array("route" => $fullPath));
        }
        $matches = array();
        $urlParams = array();
        foreach ($matches1 as $key => $value) {
            if (($key[0] == "P" && $value[1] == -1) || ($key[0] != 'X' && $key[0] != 'P'))
                continue;
            $parts = explode("_", $key);
            $prf = substr($parts[0], 1);
            unset($parts[0]);
            $cVal = implode("_", $parts);
            if ($key[0] == 'P')
                $linkName = $cVal;
            else {
                if ($value[1] != -1)
                    $urlParams[$cVal] = $value[0];
            }
        }

        if (!isset($this->definitions[$linkName])) {
            throw new RouterException(RouterException::ERR_CANT_FIND_ROUTE_DEFINITION, array("route" => $fullPath, "name" => $linkName));
        }
        // Se deben aniadir los parametros que hayan llegado por $_GET, y que no sean
        // request, output o rnd:

        $parameters=$request->getParameters();
        foreach ($parameters as $getKey => $getValue) {
            if (!in_array($getKey, array("request", "output", "rnd","site")) &&
                !isset($urlParams[$getKey])
            )
                $urlParams[$getKey] = $getValue;
        }
        // Se filtran los matches: Nos quedamos solo con las que empiezen por X, y se recortan sus nombres

        // Ahora, segun el perfil de la pagina, se ejecuta una cosa u otra.

        $this->resolveDefinition($this->definitions[$linkName], $urlParams);
    }

    /*******************************************************************************************
     *
     *            METODOS DE GESTION DE LOS DISTINTOS TIPOS DE DEFINICIONES
     *
     */
    function resolveDefinition($d, $params)
    {
        global $response;
        $response=new \lib\Response($d);
        \Registry::$registry["response"]=$response;
        // Los parametros son lo que se ha encontrado que ha hecho match en la url.
        // Sobre estos, tienen prioridad aquellos que se fijan en la definicion.
        $definedParams = isset($d["PARAMS"]) ? $d["PARAMS"] : array();
        foreach ($definedParams as $param => $paramValue) {
            $params[$param] = $this->getValueFromRoute($paramValue);
        }
        $value = $d;
        switch ($d["TYPE"]) {
            default:

            case "REDIRECT":
                $r=new \lib\routing\Redirect($value,$params,$this->request);
                $r->resolve();
                break;
            case "PAGE":
                $r=new \lib\routing\Page($value,$params,$this->request);
                $r->resolve();
                break;
            case "DATASOURCE":
                $r=\lib\routing\Datasource::getInstance($value,$params,$this->request);
                $r->resolve();
                break;
            case "ACTION":
                global $request;
                foreach ($params as $param => $paramValue)
                    $request->actionData[$param] = $paramValue;
                $action = \lib\output\json\JsonAction::fromPost();
                echo $action->execute();
                break;
        }
    }



}
?>
