<?php
include_once("CONFIG_default.php");
function __autoload($name)
{

    //set_error_handler("_load_exception_thrower");
    if(is_file(PROJECTPATH."/".str_replace('\\','/',$name).".php"))
    {

        include_once(PROJECTPATH."/".str_replace('\\','/',$name).".php");
        return;
    }
    //restore_error_handler();
}

function regenerateAll($dojoClass, $model, $datasource, $dsName)
{
    $datasource->save();

    //Ahora hay que generar la vista HTML y dojo.
    include_once(LIBPATH."/reflection/html/views/ListWidget.php");
    $listWidget=new model\reflection\Html\views\ListWidget($dsName,$model,$datasource);
    $listWidget->initialize();
    $listWidget->generateCode(false,false);
    $listWidget->save();

    $webPage=new \model\reflection\Html\pages\ViewWebPageDefinition();

    $base="";
    if($model->objectName->isPrivate())
    {
        $base=$model->objectName->getNamespaceModel()."/";
    }
    $base.=$model->objectName->getClassName();
    $webPage->create($ds,$datasource,$base,null);
    $path=$webPage->getPath();
    $paths[$path]=$webPage->getPathContents();
    $extra=$webPage->getExtraPaths();
    if($extra)
        $extraInfo[$path]=$extra;
    $webPage->save();

    // Se introduce la nueva url.Primero, hay que cargar las existentes.
    include_once(WEBROOT."/Website/Urls.php");
    $urls=new Website\Urls();
    $curPaths=$urls->getPaths();

    $pathTree=$path;
    $parts=explode("/",$pathTree);
    array_shift($parts);

    $len=count($parts);
    $keys=array_keys($curPaths);

    $position=& $curPaths[$keys[0]];
    $k=0;
    for($k=0;$k<$len-1;$k++)
    {
        $position=& $position["SUBPAGES"]["/".$parts[$k]];
    }

    $position["SUBPAGES"]["/".$parts[$k]]=$paths[$path];

    $pathHandler=new \model\reflection\Html\UrlPathDefinition($curPaths[$keys[0]]["SUBPAGES"]);
    $pathHandler->save();

    $code=$dojoClass->generateDatasourceView($dsName,$datasource);
    $dojoClass->saveDatasource($dsName, $code);

    return 'DATASOURCE ' . $dsName . 'regenerado completo';
}

function regenerateDojo($dojoClass, $model, $datasource, $dsName)
{
    $code=$dojoClass->generateDatasourceView($dsName,$datasource);
    $dojoClass->saveDatasource($dsName, $code);

    return 'DATASOURCE ' . $dsName . 'regenerado solo con Dojo';
}

function regenerateTranslations($dojoClass, $model, $datasource, $dsName)
{
    $dojoClass->regenerateTranslations($dsName, $datasource);

    return 'DATASOURCE ' . $dsName . 'regenerado solo con traducciones';
}

include_once(LIBPATH . "/model/types/BaseType.php");
include_once(LIBPATH . "/php/debug/Debug.php");
include_once(LIBPATH . "/Registry.php");
// Se listan todos los objetos que hay.
include_once(LIBPATH . "/reflection/SystemReflector.php");
\model\reflection\ReflectorFactory::loadFactory();
global $APP_NAMESPACES;
$layers=& $APP_NAMESPACES;
$layer=$_GET["layer"];
$obj=$_GET["object"];
$action=$_GET["action"];
$model=\model\reflection\ReflectorFactory::getModel($obj);

include_once(LIBPATH."/reflection/js/dojo/DojoGenerator.php");
$dojoClass=new \model\reflection\Js\dojo\DojoGenerator($model);

$ds = $_GET['datasource'];
if (! $ds) {
    throw new \RuntimeException('No se ha indicado datasource');
}

if ($ds === 'all') {
    $datasources=$model->getDataSources();
    foreach ($datasources as $dKey=>$dValue) {
        $dValue->save();
        $code=$dojoClass->generateDatasourceView($dKey,$dValue);
        $dojoClass->saveDatasource($dKey,$code);
    }
    
    echo 'TODOS LOS DATASOURCES REGENERADOS';
}
else {
    $datasource = $model->getDataSource($ds);

    switch($action) {
        case 'all':
            $msg = regenerateAll($dojoClass, $model, $datasource, $ds);
            break;
        case 'dojo':
            $msg = regenerateDojo($dojoClass, $model, $datasource, $ds);
            break;
        case 'translations':
            $msg = regenerateTranslations($dojoClass, $model, $datasource, $ds);
            break;
        default:
            throw new \RuntimeException('action not available');
    }

    echo $msg;
}