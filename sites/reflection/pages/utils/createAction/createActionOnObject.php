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
include_once(LIBPATH . "/model/types/BaseType.php");
include_once(LIBPATH . "/php/debug/Debug.php");
include_once(LIBPATH . "/Registry.php");
// Se listan todos los objetos que hay.
include_once(LIBPATH . "/reflection/SystemReflector.php");
\model\reflection\ReflectorFactory::loadFactory();

$packages=\model\reflection\ReflectorFactory::getPackageNames();
for($kk=0;$kk<count($packages);$kk++) {
    $package = $packages[$kk];
    $pkg = new \model\reflection\Package($package);
    $cLayer = $pkg->getModels($pkg);
    echo "<h2>Layer : ".$package."</h2>";


    foreach($cLayer as $key=>$value)
    {
        if($_GET["existing"]!=1)
            echo '<a href="designActionOnObject.php?layer='.$layers[$k].'&object='.$key.'">'.$key.'</a><br>';
        else
            echo '<a href="chooseActionOnObject.php?layer='.$layers[$k].'&object='.$key.'">'.$key.'</a><br>';
    }
}
