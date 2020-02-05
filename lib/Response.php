<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 0:42
 */
namespace lib;

class Response
{
    var $builder;
    function __construct($def=null)
    {
        $this->builder=null;
        $this->headers=array();
    }
    function setBuilder($b)
    {
        $this->builder=$b;
    }
    function addHeader($headerName,$value)
    {
        $this->headers[$headerName]=$value;
    }
    function generate()
    {
        foreach($this->headers as $k=>$v)
        {
            header($k.": ".$v);
        }
        if($this->builder)
            echo call_user_func($this->builder);
    }
    static function redirect($url)
    {
        $red=\Registry::$registry["response"];
        $red->addHeader("Location",$url);

        return function(){};
    }
    static function generateError()
    {
        die("REQUEST ERROR!!");
    }
}
