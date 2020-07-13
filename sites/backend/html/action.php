<?php
ini_set("display_errors","on");
  set_time_limit(0);
try {
    include_once("localConfig.php");
    //include_once 'localConfig.php';
    include_once LIBPATH . '/startup.php';
    //Startup::initializeHTTPPage();
    $request = Request::getInstance();
    $router  = Registry::getService("router");
    $response=new \lib\Response();
        \Registry::$registry["response"]=$response;
    $response->setBuilder(function() use ($request){
      return $request->resolveActions();
  });
    Registry::$registry["response"]->generate();
} catch (Exception $e) {
    header('Content-type: text/html');
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

Registry::save();

?>
