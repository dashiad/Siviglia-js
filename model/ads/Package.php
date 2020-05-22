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
        } elseif ($this->isTypeSerializer($className)) {
            require_once($this->getTypeSerializersPath($className));
        } elseif ($this->isLib($className)) {
            require_once($this->getLibPath($className));
        } else {
            return $this->includeModel($className);
        }
    }
        
    public function includeModel($modelName)
    {
        $typesBase = 'model\\ads\\objects\\Comscore\\types\\';
        if (substr($typesBase, 0, strlen($modelName))===$typesBase) {
            $typeName = array_pop(explode('\\\\', $modelName));
            include_once($this->getBasePath()."/model/ads/objects/Types/types/$typeName.php");
            return;
        } else {
            parent::includeModel($modelName);
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
    
    protected function isLib($className) : bool
    {
        //$classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        $classParts = explode("\\", str_replace(ltrim($this->baseNamespace, "\\"), "", ltrim($className, "\\")));
        if (isset($classParts[1])) {
            return $classParts[1]=="lib";
        } else {
            return false;
        }
    }
    
    protected function getLibPath($className) : string
    {
        //$classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        $classParts = explode("\\", str_replace(ltrim($this->baseNamespace, "\\"), "", ltrim($className, "\\")));
        if (!isset($classParts[3])) array_push($classParts, $classParts[2]);
        $className = implode("/", $classParts);
        return $this->fullPath."$className.php";
    }
    
    protected function getTypeSerializersPath($className) : string
    {
        $classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        if (!isset($classParts[3])) array_push($classParts, $classParts[2]);
        $className = implode("/", $classParts);
        return $this->fullPath."/objects/".$className.".php";
    }
    
    protected function isTypeSerializer($className) : bool
    {
        $classParts = explode("\\", ltrim($className, ltrim($this->baseNamespace, "\\")));
        if (isset($classParts[1]) && isset($classParts[2])) {
            return ($classParts[1]=="serializers" && $classParts[2]=="types");
        } else {
            return false;
        }
    }
    
    
}
