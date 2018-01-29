<?php
$object=$_POST["object"];
$query=$_POST["query"];
$name=$_POST["name"];
function printWarning($cad)
{
    echo $cad;
}
// $object='backoffice\bag'

//$query="SELECT id_bag,id_bag_request FROM bag  WHERE [%dyn_id_bag:id_bag LIKE '{%dyn_id_bag%}%'%]";

// Se busca el objeto.

// Se busca ahora el modelo.
include_once(PROJECTPATH."/model/reflection/ReflectorFactory/ReflectoFactory.php");

$model=\model\reflection\ReflectorFactory::getModel($object);

include_once(PROJECTPATH."/model/reflection/Datasource/DataSourceDefinition.php");
$oDs=new \model\reflection\Datasource\DataSourceDefinition($_POST["name"],$model);

include_once(PROJECTPATH."/model/reflection/Storage/objects/Mysql/MysqlDsDefinition.php");
$mysqlDef=new \model\reflection\Storage\Mysql\MysqlDefinition($model,$name,$oDs);

$newDs=$mysqlDef->generateFromQuery($model,$name,$query);
$newDs->save($name);

// Ahora hay que generar la vista HTML y dojo.
include_once(LIBPATH."/reflection/html/views/ListWidget.php");
$listWidget=new model\reflection\Html\views\ListWidget($name,$model,$newDs);
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
$webPage->create($name,$newDs,$base,null);
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

?>
