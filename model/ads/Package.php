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
    
    public function getWorkersWidgetPath($className, $widgetName=null) : string
    {
        if (is_null($widgetName)) $widgetName = "JOB_DEFAULT";
        $classPath = $this->getWorkersPath($className);
        $classParts = explode('/', $classPath);
        array_pop($classParts);
        array_push($classParts, "widgets");
        array_push($classParts, $widgetName);
        $path = implode("/", $classParts);
        if (!is_file("{$path}.wid")) {
            $path = "/model/web/objects/Jobs/objects/Worker/widgets/JOB_BASE";
        } else {
            $path = str_replace(PROJECTPATH, "", $path);
        }
        
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
