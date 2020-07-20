<?php


namespace lib\model;


class GlobalContext
{
    static $instance=null;
    var $user;
    var $site;
    static function getInstance()
    {
        if(GlobalContext::$instance==null)
        {
            GlobalContext::$instance=new GlobalContext();
        }
        return GlobalContext::$instance;
    }

}