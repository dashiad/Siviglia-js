<?php

ini_set("display_errors","on");
try {
    include_once("/var/www/adtopy/install/config/localConfig.php");
    //include_once 'localConfig.php';
    include_once LIBPATH . '/startup.php';
    //Startup::initializeHTTPPage();
    $request = Request::getInstance();
    $router  = Registry::getService("router");
    $router->route($request);
    Registry::$registry["response"]->generate();
} catch (Exception $e) {
    header('Content-type: text/html');
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

Registry::save();
