<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 21/10/2017
 * Time: 0:15
 */

namespace lib;


class Paths
{
    static function getBase()
    {
        return __DIR__."/../";
    }
    static function getLib()
    {
        return __DIR__;
    }
    function getDojoActionJs($namespace,$model,$actionName)
    {
        return Paths::getBase()."/".$namespace."/objects/".$model."/js/dojo/actions/".$actionName."Action.js";
    }
    function getDojoActionTemplate($namespace,$model,$actionName)
    {
        return Paths::getBase()."/".$namespace."/objects/".$model."/js/dojo/actions/templates/".$actionName.".html";
    }
    function getRelativeSitePath()
    {
        return "/sites";
    }
    function getSitePath()
    {
        return Paths::getBase().Paths::getRelativeSitePath();
    }
    function getSiteNamespace()
    {
        return '\\sites';
    }
    function getSiteDocumentRoot($siteName)
    {
        return Paths::getBase()."/html/".$siteName;
    }
    function getModelPath()
    {
        return Paths::getBase()."/model";
    }
}