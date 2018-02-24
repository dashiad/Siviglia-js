<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 17/12/2017
 * Time: 12:20
 */

namespace model\web\Site;


abstract class SiteConfig
{
    const CONFIG_KEY_SERVICES="services";
    const CONFIG_KEY_USER="user";
    const CONFIG_KEY_PERMISSIONS="permissions";
    abstract function getDefinition();
    function getUserConfig()
    {
        $def=$this->getDefinition();
        return $def[SiteConfig::CONFIG_KEY_SERVICES][SiteConfig::CONFIG_KEY_USER];
    }
    function getPermissions()
    {
        $def=$this->getDefinition();
        return $def[SiteConfig::CONFIG_KEY_SERVICES][SiteConfig::CONFIG_KEY_PERMISSIONS];

    }
    function getDefaultIso()
    {
        $def=$this->getDefinition();
        return io($def,"DEFAULT_ISO","en");
    }
    function getStaticsSite()
    {
        $def=$this->getDefinition();
        return io($def,"STATICS_SITE",null);
    }
    function getExtraWidgetPath()
    {
        $def=$this->getDefinition();
        return io($def,"WIDGETPATH",array());

    }
}