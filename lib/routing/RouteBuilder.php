<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 1:39
 */

namespace lib\routing;
class RouteBuilderException extends \lib\model\BaseException
{
    const ERR_CANT_FIND_ROUTE_DIR=1;
}


class RouteBuilder
{
    var $routeSpec;
    function __construct($routeSpec)
    {
        $this->routeSpec=$routeSpec;
    }
    static function rebuildSiteUrls($site)
    {
        return $site->rebuildSiteUrls();
    }

    function regenerateCache($cachePath)
    {
        $routes = array();
        $regexes = array();
        $definitions = array();

        foreach ($this->routeSpec as $key => $val) {
            $routes = array_merge_recursive($routes, $this->loadDefinitions($val["Urls"], $val["namespace"], 'urls',"",true));
            $this->counter = 0;
            $paths = $this->buildPaths($routes);
            for ($k = 0; $k < count($paths["REGEX"]); $k++)
                $regexes[] = "~^" . $paths["REGEX"][$k] . "(?:\\?.*){0,1}(?:&.*){0,1}$~";
            $definitions = array_merge($definitions, $this->loadDefinitions($val["Definitions"], $val["namespace"], 'Definitions',"",false));
        }
        $dir=basename($cachePath);
        if(!is_dir($dir))
            mkdir($dir,0777,true);
        file_put_contents($cachePath, serialize(array("DEFINITIONS" => $definitions, "PATHS" => $routes, "REGEX" => $regexes)));
    }

    function loadDefinitions($path, $baseNamespace, $classNamespace,$prefix="",$usePrefix=false)
    {
        if($usePrefix==false)
            $prefix="";
        $definition = array();
        $dir = opendir($path);
        if (!$dir) {
            throw new RouteBuilderException(RouteBuilderException::ERR_CANT_FIND_ROUTE_DIR, array("route" => $path));
        }

        while ($file = readdir($dir)) {
            if ($file == "." || $file == "..")
                continue;
            if (is_dir($path . "/" . $file)) {
                $defs = $this->loadDefinitions($path . "/" . $file, $baseNamespace, $classNamespace . "\\" . $file,$prefix."/".$file,$usePrefix);
                $definition = array_merge_recursive($definition, $defs);
            } else {
                include_once($path . "/" . $file);
                $className = $baseNamespace . '\\' . $classNamespace . '\\' . basename($file, ".php");
                $d=$className::$definition;
                foreach($d as $k=>$v)
                {
                    $definition[$prefix.$k]=$v;
                }
            }
        }
        return $definition;
    }

    var $counter = 0;

    function buildPaths($paths, $inParam = 0)
    {

        $regexes = array();
        $pathArray = array();
        foreach ($paths as $key => $value) {
            // If the current path has a LAYOUT defined, it's an entry point,
            // so its current path is stored.
            $checkingParam = false;
            // Se procesa la key, sustituyendo todo lo que hay entre { } por la expresion regular correspondiente.
            // El caracter delimitador depende de si estamos en query string o no
            // Como los parametros nombrados no pueden duplicarse, y, ademas, requieren que el primer caracter
            // no sea numerico, se le pone un prefijo "P"+curIndex.
            // Para ello, vamos a necesitar hacer un closure dentro de preg_replace_callback, que vaya
            // incrementando el curIndex.
            $f = function ($matches) {
                $this->counter++;
                // Si comienza por "*" significa que va a hacer match desde ese elemento del path, hasta el final
                // es decir, mientras /a/{param}/b  hace match con /a/q/b , y param==q,
                // la ruta /a/{*param} hace match con /a/q/b , y param==q/b
                $paramName = $matches[1];
                $stopConditions = "/&";
                if ($matches[1][0] == "*") {
                    $paramName = substr($matches[1], 1);
                    $stopConditions = "?&";
                }
                return "(?P<X" . $this->counter . "_" . $paramName . ">[^" . $stopConditions . "]+)";
            };

            if ($inParam == 0 && strpos($key, "?") !== false)
                $inParam = 1;

            $subRegex = str_replace(array("?", "[", "]"), array("\\?", "\\[", "\\]"), $key);

            $subRegex = preg_replace_callback("/{([^}]*)}/", $f, $subRegex);
            if ($subRegex[0] != "/" && $inParam == 0)
                $subRegex = "/" . $subRegex;

            // Si bajo esta clave hay un array, es que son subpaginas
            if (is_array($value)) {
                $results = $this->buildPaths($value, $inParam);
                $childRegex = $results["REGEX"];
                $regexes[] = $subRegex . "(?:(?:" . implode(")|(?:", $childRegex) . "))";
                $subpaths = array();
                $paths2 = $results["PATHS"];
                foreach ($paths2 as $key2 => $value2) {
                    $pathArray[$key2] = ($inParam == 0 ? "/" : "") . $key . $value2;
                }
            } else {
                // Es una clave final.Debe ser el nombre del link
                // Hay que poner un .* antes del nombre del link, para asegurarnos de que cualquier parametro GET
                // recibido, no acaba siendo considerado el nombre del link.
                // Ademas, si en la subRegex no hay ninguna "?", se la ponemos, para evitar que
                // si el path es /a/b/c, la regex sea al menos /a/b/c?... (con ?... opcional), para que
                // /a/b/chkjlkjl no haga match con /a/b/c

                // Si no es un array el final, se eliminan "/" espureas al final

                if ($key != "/")
                    $subRegex = rtrim($subRegex, "/");
                if (strpos($subRegex, "?") === false) {
                    $subRegex = "(?P<P" . $this->counter . "_" . $value . ">" . $subRegex . ")";
                } else {
                    $subRegex = "(?P<P" . $this->counter . "_" . $value . ">" . $subRegex . ".*)";
                }
                $this->counter++;
                $pathArray[$value] = ($inParam == 0 ? "/" : "") . $key;
                $regexes[] = $subRegex;
            }
        }
        // Se hace implode de las regexes
        //if(count($regexes)>1)
        //{
        //    $r="(?:(?:".implode(")|(?:",$regexes)."))";
        //}
        //else
        //    $r=$regexes[0];

        return array("REGEX" => $regexes, "PATHS" => $pathArray);
    }


}