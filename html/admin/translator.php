<?php
/**
 * Punto de entrada para los ficheros js y html de Dojo. Así conseguimos que puedan traducirse por el
 * mismo sistema de traducciones del back.
 */
include_once("CONFIG_default.php");
include_once(LIBPATH."startup.php");
include_once(LIBPATH."/Request.php");

function getSuffixFromPath($path)
{
    $info = pathinfo($path);
    return $info['extension'];
}

function getLayoutFromPath($path)
{
    $path = ltrim($path, '/'); //Quitar la primera barra

    //Si es un path de /lib/custom, entonces devolverlo tal cual
    if (strstr($path, 'lib/custom/')) {
        return 'html/Application/'.$path;
    }

    $result = array();
    $result[] = 'backoffice';
    $result[] = 'objects';

    $parts = explode('/', $path);
    unset($parts[0]);
    unset($parts[1]);
    unset($parts[2]);
    $dojo = array_search('dojo', $parts);
    if ($dojo !== false) {
        unset($parts[$dojo]);
    }

    //Reindex
    $parts = array_values($parts);

    if (strstr($path, '/objects/')) {
        //Objecto secundario
        $result[] = $parts[0]; //Objeto principal
        $result[] = 'objects';
        $result[] = $parts[2]; //Objeto secundario
        $start = 3;
    }
    else {
        //Objeto primario
        $result[] = $parts[0]; //Objeto principal
        $start = 1;
    }

    $result[] = 'js';
    $result[] = 'dojo';

    //Las partes que quedan son iguales, las pasamos tal cual
    $n = count($parts);
    for($i=$start;$i<$n;$i++) {
        $result[] = $parts[$i];
    }

    $result = implode('/', $result);

    return $result;
}

function getCacheDirectoryFromPath($path)
{
    $path = ltrim($path, '/'); //Quitar la primera barra

    if (strstr($path, 'lib/custom/')) {
        $parts = explode('/', $path);
        unset($parts[count($parts)-1]);
        $result = implode('/', $parts);

        return $result;
    }

    $parts = explode('/', $path);
    unset($parts[count($parts)-1]);
    unset($parts[0]);
    unset($parts[1]);
    unset($parts[2]);

    $result = implode('/', $parts);

    return $result;
}

$request=Request::getInstance();
Registry::initialize($request);
$params=$request->getParameters();

include_once(LIBPATH."output/html/templating/TemplateParser2.php");
include_once(LIBPATH."output/html/templating/TemplateHTMLParser.php");

$subpath = $request->parameters['subpath'];
$httpLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
switch($httpLang) {
    case 'es':
        $language='percentil';
        break;
    case 'de':
        $language='de';
        break;
    default:
        $language='percentil';
}

$pluginPath = array(
    "L"=>array(
        "lang"=>$language,
        "LANGPATH"=>CUSTOMPATH."/lib/templating/lang/",
        "realm"=>'Back',
    )
);

$oLParser=new \CLayoutHTMLParserManager();
$oManager=new \CLayoutManager(CUSTOMPATH."/../","html",array(),$pluginPath);

$layout = PROJECTPATH.getLayoutFromPath($subpath);
if (!file_exists($layout)) {
    http_response_code(404);
    exit();
}

$cacheDir = getCacheDirectoryFromPath($subpath);
$definition=array(
    "LAYOUT"=>$layout,
    "CACHE_SUFFIX"=>getSuffixFromPath($subpath),
    "TARGET"=>PROJECTPATH."/cache/dojo/".$language."/".$cacheDir."/",
);

$content = $oManager->renderLayout($definition,$oLParser,false);
echo $content;
exit();