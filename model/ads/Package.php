<?php
/**
 * Class Package
 * @package model\ads
 *  (c) Smartclip
 */


namespace model\ads;


use lib\model\ModelService;

class Package extends \lib\model\Package
{
    const WORKERS_DIRNAME = "WORKERS";
    
    public function includeFile($className)
    {
        if($this->isWorker($className)) {
            require_once($this->getWorkersPath($className));
        } else {
            return $this->includeModel($className);
        }
    }
        
    protected function getWorkersPath($className) : string
    {
        $classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        if (!isset($classParts[3])) array_push($classParts, $classParts[2]);
        $className = implode("/", $classParts);
        return $this->fullPath."/objects/".$className.".php";
    }
    
    public function getWorkersWidgetPath($className, $widgetName="JOB_DEFAULT") : string
    {
        $classPath = $this->getWorkersPath($className);
        $classParts = explode('/', str_replace("\\", "/", $classPath));
        array_pop($classParts);
        array_push($classParts, "widgets");
        array_push($classParts, $widgetName);
        $path = implode("/", $classParts);
        $path = str_replace(str_replace("\\", "/", PROJECTPATH), "", $path);
        return $path;
    }
    
    protected function isWorker($className) : bool
    {
        $classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        if (isset($classParts[1])) {
            return strtoupper($classParts[1])==self::WORKERS_DIRNAME;
        } else {
            return false;
        }
    }
}
