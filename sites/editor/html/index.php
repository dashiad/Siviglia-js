<?php
ini_set("html_errors","on");
ini_set("display_errors","on");
try {
    include_once 'localConfig.php';
    include_once LIBPATH . '/startup.php';
    Startup::initializeHTTPPage();

    $request = Request::getInstance();
    $router=new \lib\Router();
    $router->route($request);
    Registry::$registry["response"]->generate();
} catch (Exception $e) {
    header('Content-type: text/html');

    print_r($e);
}

Registry::save();
