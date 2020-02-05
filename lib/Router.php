<?php
namespace lib;
use lib\php\ParametrizableString;

class RouterException extends \lib\model\BaseException
{
    const ERR_PAGE_NOT_FOUND=1;
    const ERR_CANT_FIND_ROUTE_DEFINITION=2;
    const ERR_REQUIRED_PARAMETER=3;
    const TXT_PAGE_NOT_FOUND="Page not found";
    const TXT_CANT_FIND_ROUTE_DEFINITION="No se encuentra la definicion de la ruta [%routeName%]";
    const TXT_REQUIRED_PARAMETER="Parametro de construccion de url no encontrado : [%paramName%]";
}

class Router
{
    var $definitions;
    var $request;
    function __construct()
    {
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
        $response=new \lib\Response();
        \Registry::$registry["response"]=$response;

        $subpath=$request->getRequestedPath();

        /*   BEGIN  */
        if($subpath[0]!="/")
            $subpath="/".$subpath;
        $trimmed=trim(str_replace("//","/",$subpath),"/");
        $parts=explode("/",$trimmed);

        switch($parts[0])
        {
            case "action":{

                $response->setBuilder(function() use ($request){
                    return $request->resolveActions();
                });
                return;

            }break;
            case "datasource":{
                $regex="#datasource/(.*)/([^/?]+)#";
                if(preg_match($regex,$subpath,$matches))
                {
                    $value["MODEL"]=$matches[1];
                    $value["NAME"]=$matches[2];
                    $r=\lib\routing\Datasource::getInstance($value,$request->getParameters(),$this->request);
                    $r->resolve();
                }
                return;
            }break;
            case "js":{
                if(!isset($parts[1]))
                    die();
                $handler='\lib\output\html\renderers\js\\'.ucfirst(strtolower($parts[1]));
                if(!class_exists($handler))
                    die();
                $instance=new $handler();
                $response->setBuilder(function() use ($instance,$subpath,$parts){
                    return $instance->resolve($subpath,$parts);
                });
                return;
            }break;
        }

        $site = \model\web\Site::getCurrentWebsite();

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


    /*
     * Genera una url dando el nombre de la url, y una lista de parametros.
     */
    var $revPaths=null;
    function generateUrl($name, $params)
    {
        if($this->revPaths==null)
            $this->revPaths=array_flip($this->paths);
        if(is_object($params))
            $p=$params->getFields();
        else
            $p=$params;

        $definitions = $this->definitions;

        if (isset($definitions[$name])) {

            $curUrl=$this->revPaths[$name];
            $usedParams=array();
            $f = function ($matches) use ($params,& $usedParams){

                // Si comienza por "*" significa que va a hacer match desde ese elemento del path, hasta el final
                // es decir, mientras /a/{param}/b  hace match con /a/q/b , y param==q,
                // la ruta /a/{*param} hace match con /a/q/b , y param==q/b
                $paramName = $matches[1];
                if($matches[1][0]=="*")
                    $paramName=substr($matches[1],1);
                if(!isset($params[$paramName]))
                    throw new RouterException(RouterException::ERR_REQUIRED_PARAMETER,array("paramName"=>$paramName));
                $usedParams[]=$paramName;
                return $params[$paramName];
            };

            $curUrl=preg_replace_callback("/{([^}]*)}/", $f, $curUrl);
            for($k=0;$k<count($usedParams);$k++)
                unset($p[$usedParams[$k]]);
            if(count(array_keys($p))>0)
            {
                $curUrl.="?";
                $parts=array();
                foreach($p as $k=>$v)
                {
                    if($v[0]=="$")
                        $parts[]=$k."=".$v;
                    else
                        $parts[]=$k."=".urlencode($v);
                }
                $curUrl.=implode("&",$parts);
            }
            return $curUrl;
        }
        throw new RouterException(RouterException::ERR_CANT_FIND_ROUTE_DEFINITION,array("routeName"=>$name));

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


        // Si el path no es "/", quitamos la "/" inicial

        $fullPath = urldecode($fullPath);
        $n = count($this->regexp);
        $maxMatch=0;
        $curMatch=null;
        for ($k = 0; $k < $n; $k++)
        {
            if($res = preg_match($this->regexp[$k], $fullPath, $matches1, PREG_OFFSET_CAPTURE))
            {
                $nMatches=count($matches1);
                if($nMatches > $maxMatch)
                {
                    $curMatch = $matches1;
                    $maxMatch=$nMatches;
                }
            }
        }

        if (!$curMatch) {
            throw new RouterException(RouterException::ERR_PAGE_NOT_FOUND, array("route" => $fullPath));
        }
        $urlParams = array();
        foreach ($curMatch as $key => $value) {
            if (($key[0] == "P" && $value[1] == -1) || ($key[0] != 'X' && $key[0] != 'P'))
                continue;
            $parts = explode("_", $key);
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

        $this->resolveDefinition($linkName,$this->definitions[$linkName], $urlParams);
    }

    /*******************************************************************************************
     *
     *            METODOS DE GESTION DE LOS DISTINTOS TIPOS DE DEFINICIONES
     *
     */
    function resolveDefinition($name,$d, $params)
    {
        global $response;
        $this->request->setCurrentRoute($name,$d);
        $response=new \lib\Response($d);
        \Registry::$registry["response"]=$response;
        // Los parametros son lo que se ha encontrado que ha hecho match en la url.
        // Sobre estos, tienen prioridad aquellos que se fijan en la definicion.
        $definedParams = isset($d["PARAMS"]) ? $d["PARAMS"] : array();
        foreach ($definedParams as $param => $paramValue) {
            $params[$param] = $paramValue;
        }
        $value = $d;
        // Se permite que las paginas, en su definicion, fuercen un tipo de salida independientemente de los parametros GET recibidos.
        if(isset($d["RESPONSE"])) {
            if (isset($d["RESPONSE"]["TYPE"])) {
                switch (strtolower($d["RESPONSE"]["TYPE"])) {
                    case "json":{
                        $this->request->setOutputType(\Request::OUTPUT_JSON);
                    }break;

                }
            }
        }
        switch ($d["TYPE"]) {
            default:
            case "META":
            {
                $r=new \lib\routing\Meta($value,$params,$this->request);
                $r->resolve();
                break;
            }break;
            case "REDIRECT":
                $r=new \lib\routing\Redirect($value,$params,$this->request);
                $r->resolve();
                break;
            case "PAGE":

                $r=new \lib\routing\Page($d["PAGE"],$value,$params,$this->request);
                $r->resolve();
                break;
            case "DATASOURCE":
                $value["MODEL"]=$params["modelPath"];
                $value["NAME"]=$params["dsName"];
                unset($params["modelPath"]);
                unset($params["dsName"]);
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
