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
