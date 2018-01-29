<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Usuario
 * Date: 22/09/13
 * Time: 18:16
 * To change this template use File | Settings | File Templates.
 */
include_once("CONFIG_default.php");
include_once(PROJECTPATH."/lib/startup.php");

//spl_autoload_register(mainAutoloader);

include_once(LIBPATH . "/model/types/BaseType.php");
//include_once(LIBPATH . "/php/debug/Debug.php");
include_once(LIBPATH . "/Registry.php");
// Se listan todos los objetos que hay.
include_once(LIBPATH . "/reflection/SystemReflector.php");
\model\reflection\ReflectorFactory::loadFactory();
$layers=\model\reflection\ReflectorFactory::$layers;
global $APP_NAMESPACES;
$layers=& $APP_NAMESPACES;

$action=io($_GET,"action",null);
switch($action)
{
    case 'dojods':
    {
        $link="regenerateDojo.php";
    }break;
    case 'list':
    {
        $link="showObject.php";
    }break;
    default:
        {
            $link="regenerate.php";
        }break;
}
for($k=0;$k<count($layers);$k++)
{
    echo "<h2>Layer : ".$layers[$k]."</h2>";
    $cLayer=\model\reflection\ReflectorFactory::getObjectsByLayer($layers[$k]);
    foreach($cLayer as $key=>$value)
    {
        $extra="";
        if(isset($_GET["existing"]))
                $extra="&existing=1";
        echo '<a href="'.$link.'?layer='.$layers[$k].'&object='.$key.$extra.'">'.$key.'</a><br>';
    }
}
