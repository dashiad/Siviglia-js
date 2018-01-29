<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 3:30
 */

namespace lib\routing;


class Page
{
    var $request;
    var $pageInstance;
    var $definition;
    var $params;
    function __construct($def,$params,$request)
    {
        $this->request=$request;
        $this->definition=$def;
        $this->params=$params;
        global $currentPage;
        $this->pageInstance = \model\web\Page::getPageFromPath($def["PAGE"],io($def,"SITE",null),$request,$params);
        if($this->pageInstance!=null)
        {

            $currentPage = $this->pageInstance;
            \Registry::addService("page", $this->pageInstance);
            \Registry::store("currentPage", $this->pageInstance);
        }
    }

    function resolve()
    {

        $response=\Registry::$registry["response"];
        $m=$this;
        if($this->pageInstance!=null) {
            if($this->pageInstance->canAccess(\Registry::getService("permissions"),\Registry::getService("user"))) {
                $this->pageInstance->initializePage($this->params, $this->request);
                $response->setBuilder(function () use ($m) {
                    $this->pageInstance->render($this->request->getOutputType(), $m->request, isset($m->definition["OUTPUT_PARAMS"]) ? $m->definition["OUTPUT_PARAMS"] : array());
                });
            }
            else
                $this->onUnauthorized($response);
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