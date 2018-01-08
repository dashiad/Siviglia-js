<?php

abstract class Request implements \ArrayAccess
{
    var $parameters;
    var $client;
    var $clientType;
    var $urlCandidate;
    const OUTPUT_JSON="json";
    const OUTPUT_XLS="xls";
    const OUTPUT_HTML="html";
    const OUTPUT_XML="xml";
    static $instance;
    static $request;

    private function __construct()
    {                

    }
    static function getInstance()
    {        
        if( Request::$instance!=null) {
            return Request::$instance;
        }

       if(defined("STDIN")) {
           include_once(LIBPATH."/output/commandLine/CLRequest.php");
           Request::$instance=new \lib\output\commandLine\CLRequest();
       }
       else {
           // suponemos http
           include_once(LIBPATH."/output/html/HTMLRequest.php");
            Request::$instance=new \lib\output\html\HTMLRequest();
       }
        Request::$request=Request::$instance;
        return Request::$instance;
    }
    abstract function getCurrentSite();
    abstract function getParameters();
    abstract function getActionData();
    abstract function getClientData();

    abstract function getUser();
    abstract function getOutputType();
    abstract function getUrl();
}

