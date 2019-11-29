<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 3:30
 */

namespace lib\routing;

use Registry;

class Page
{
    var $request;
    var $pageInstance;
    var $definition;
    var $params;
    var $name;

    function __construct($name,$def,$params,$request)
    {
        $this->name=$name;
        $this->request=$request;
        $this->definition=$def;
        $this->params=$params;
        global $currentPage;
        $this->pageInstance = \model\web\Page::getPageFromName($name,io($def,"SITE",null),$request,$params);
        if($this->pageInstance!=null)
        {
            $currentPage = $this->pageInstance;
            Registry::$registry[Registry::SERVICE_CONTAINER]->addService("page",$this->pageInstance);
            \Registry::store("currentPage", $this->pageInstance);
        }

    }

    function resolve()
    {

        $response=\Registry::$registry["response"];
        $m=$this;
        if($this->pageInstance!=null) {
            try{
            $this->pageInstance->checkPermissions(\Registry::getService("user"));
            }
            catch(\model\web\PageException $e)
            {
                $this->onUnauthorized($response);
                return;
            }
            $this->pageInstance->initializePage($this->pageInstance->getPageDefinitionObject(), $this->request);
            $response->setBuilder(function () use ($m) {
                $this->pageInstance->render($this->request->getOutputType(), $m->request, isset($m->definition["OUTPUT_PARAMS"]) ? $m->definition["OUTPUT_PARAMS"] : array());
                });
        }
        else
            $this->onError($response);

    }
    function onUnauthorized($response)
    {
        $router=\Registry::getService("router");
        $response->setBuilder(\lib\Response::redirect($router->generateUrl("error",array())));
    }
    function onError($response)
    {
        $router=\Registry::getService("router");
        $response->setBuilder(\lib\Response::redirect($router->generateUrl("error",array())));
    }
}
