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
\lib\reflection\ReflectorFactory::loadFactory();
global $APP_NAMESPACES;
$layers=& $APP_NAMESPACES;
$layer=$_GET["layer"];
$obj=$_GET["object"];
$model=\lib\reflection\ReflectorFactory::getModel($obj);

include_once(LIBPATH."/reflection/js/dojo/DojoGenerator.php");
$dojoClass=new \lib\reflection\js\dojo\DojoGenerator($model);

$type = $_GET['type'];

$act = $_GET['action'];
if (! $act) {
    throw new \RuntimeException('No se ha indicado action');
}

if ($act === 'all') {
    $actions = $model->getActions();
    foreach ($actions as $aKey=>$aValue) {
        $aValue->save();

        include_once(LIBPATH.'/reflection/html/forms/FormDefinition.php');
        $formInstance=new \lib\reflection\html\forms\FormDefinition($aKey,$aValue);
        $formInstance->create();
        $formInstance->saveDefinition();
        $formInstance->generateCode();

        $a = $formInstance->getDefinition();

        $code=$dojoClass->generateForm($aKey,$formInstance);
        $dojoClass->saveForm($aKey,$code);
    }

    echo 'TODAS LAS ACTIONS REGENERADAS';
}
else {
    $action = $model->getAction($act);

    if ($type === 'all') {
        $action->save();
        $suffix = 'completa';
    }
    else {
        $suffix = 'solo con dojo';
    }

    include_once(LIBPATH.'/reflection/html/forms/FormDefinition.php');
    $formInstance=new \lib\reflection\html\forms\FormDefinition($act,$action);
    $formInstance->create();
    $formInstance->saveDefinition();
    $formInstance->generateCode();

    $a = $formInstance->getDefinition();

    $code=$dojoClass->generateForm($act,$formInstance);
    $dojoClass->saveForm($act,$code);

    echo 'ACTION ' . $act . ' regenerada ' . $suffix;
}