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
    function __construct($def,$params,$request)
    {
        $this->request=$request;
        $this->definition=$def;
        global $currentPage;
        $this->pageInstance = \model\web\Page::getPageFromPath($def["PAGE"],$request,$params);

        if($this->pageInstance!=null)
        {
            $this->pageInstance->initializePage($params, $request);
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
            $response->setBuilder(function () use ($m) {
                $this->pageInstance->render($this->request->getOutputType(), $m->request, isset($m->definition["OUTPUT_PARAMS"]) ? $m->definition["OUTPUT_PARAMS"] : array());
            });
        }
        else
        {
            $router=\Registry::getService("router");

            $response->setBuilder(\lib\Response::redirect($router->generateUrl("error",array())));
        }
    }
}