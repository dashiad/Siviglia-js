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
    static function getDojoActionJs($namespace,$model,$actionName)
    {
        return PROJECTPATH."/".$namespace."/objects/".$model."/js/dojo/actions/".$actionName."Action.js";
    }
    static function getDojoActionTemplate($namespace,$model,$actionName)
    {
        return PROJECTPATH."/".$namespace."/objects/".$model."/js/dojo/actions/templates/".$actionName.".html";
    }
    static function getRelativeSitePath()
    {
        return "/sites";
    }
    static function getSitePath()
    {
        return PROJECTPATH.Paths::getRelativeSitePath();
    }
    static function getSiteNamespace()
    {
        return '\\sites';
    }
    static function getSiteDocumentRoot($siteName)
    {
        return PROJECTPATH."/html/".$siteName;
    }
    static function getModelPath()
    {
        return PROJECTPATH."/model";
    }
}