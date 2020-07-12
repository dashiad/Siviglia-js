<?php
ini_set("html_errors","on");
ini_set("display_errors","on");

try {
    include_once '/vagrant/localConfig.php';
    include_once LIBPATH . '/startup.php';
    //Startup::initializeHTTPPage();

    $request = Request::getInstance();
    $router  = Registry::getService("router");
    $router->route($request);
    Registry::$registry["response"]->generate();
} catch (Exception $e) {
    header('Content-type: text/html');
    if(is_a($e,'\lib\BaseException'))
    {
        echo $e->getTraceAsString();

    }
    var_dump($e);
    echo "Excepcion:".get_class($e)." Codigo: ".$e->getCode()."<br>";
    if(isset($e->xdebug_message))
        echo $e->xdebug_message;
}

Registry::save();
