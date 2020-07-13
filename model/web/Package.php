<?php
/**
 * Class Package
 * @package model\ads
 *  (c) Smartclip
 */
namespace model\web;

class Package extends \lib\model\Package
{    
    const APP_DIRNAME = 'APP';
    protected $apps = ['Jobs'];
    
    public function includeFile($className)
    {
        if($this->isApp($className)) {
            require_once($this->getAppPath($className));
        } else {
            return $this->includeModel($className);
        }
    }
    
    protected function getAppPath($className) : string
    {
        $className = str_replace("\\", "/", ltrim($className, ltrim($this->baseNamespace, "\\")));
        return $this->fullPath."/objects/".$className.".php";
    }
    
    protected function isApp($className) : bool
    {
        $classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        if (in_array($classParts[0], $this->apps)) {
            return strtoupper($classParts[1])==self::APP_DIRNAME;
        } else {
            return false;
        }
        //return in_array($classParts[0], $this->apps);
    }
}