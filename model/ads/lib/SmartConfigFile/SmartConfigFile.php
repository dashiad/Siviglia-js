<?php
namespace model\ads\lib\SmartConfigFile;

use lib\model\BaseException;
use phpDocumentor\Reflection\Types\String_;

class SmartConfigFile
{
    const DEFAULT_REGEX = ".*";
    
    protected $domain;
    protected $plugin;
    protected $regex;
    protected $config;
    protected $savedConfig;
    
    protected $__isLoaded = false;
    protected $__hasError = false;
    protected $__isSaved  = true;
    
    public function __construct(String $domain)
    {
        $this->domain = $domain;
    }
    
    /**
     * 
     * @param String $plugin
     * @param String $regex
     * @return array|NULL
     */
    public function getConfig(String $plugin, String $regex=self::DEFAULT_REGEX) : ?Array
    {
        $this->stopIfError();
        try {
            $this->config  = $this->loadJson();
            $this->savedConfig = $this->config;
            $this->plugin   = $plugin;
            $this->regex    = $regex;
            $this->__isLoaded = true;
        } catch (\SmartConfigFileException $e) {
            $this->__hasError = true;
        }
        return $this->config;
    }
    
    /**
     * 
     * @param array $config
     * @return Bool
     */
    public function setConfig(Array $config) : Bool
    {
        $this->stopIfError();
        $this->stopIfUnloaded();
        $this->config = $config;
        $this->__isSaved = false;
        return true;
    }
    
    /**
     * 
     * @throws SmartConfigFileException
     * @return Bool
     */
    public function saveConfig() : Bool
    {
        $this->stopIfError();
        $this->stopIfUnloaded();
        try {
            if (!$this->__isSaved) {
                //TODO: guardar config
                $this->__isSaved = true;
            }
        } catch (\Exception $e) {
            throw new SmartConfigFileException(SmartConfigFileException::ERR_SAVING_CONFIG);
        }
        return true;
    }
    
    public function isLoaded() : Bool
    {
        return $this->__isLoaded;
    }
    
    public function isSaved() : Bool
    {
        return $this->__isSaved;
    }
    
    public function hasError() : Bool
    {
        return $this->__hasError;
    }
    
    public function getDomain() : String
    {
        return $this->domain;
    }
    
    public function getRegex() : String
    {
        $this->stopIfUnloaded();
        return $this->regex;
    }
    
    public function getPlugin() : String
    {
        $this->stopIfUnloaded();
        return $this->plugin;
    }
    
    public function getConfig() : String
    {
        $this->stopIfUnloaded();
        return $this->config;
    }
    
    public function resetConfig() 
    {
        $this->stopIfUnloaded();
        $this->stopIfError();
        $this->config = $this->savedConfig;
        $this->__isSaved = true;
    }
    
    /**
     * 
     * @throws SmartConfigFileException
     * @return array
     */
    protected function loadJson() : Array
    {
        if ($this->__isLoaded) {
            $config = $this->config;
        } else {
            try {
                $config = []; // TODO: cargar archivo y extraer configuración
                $this->config = $config;
                $this->__isLoaded = true;
            } catch (\Exception $e) {
                $this->__hasError = true;
                throw new SmartConfigFileException(SmartConfigFileException::ERR_CONFIG_NOT_FOUND);
            }
        }
        return $config;
    }
    
    /**
     * 
     * @throws SmartConfigFileException
     */
    protected function stopIfError()
    {
        if ($this->__hasError) {
            throw new SmartConfigFileException(SmartConfigFileException::ERR_PREVIOUS_ERROR);
        }
    }
    
    /**
     * 
     * @throws SmartConfigFileException
     */
    protected function stopIfUnloaded()
    {
        if ($this->__hasError) {
            throw new SmartConfigFileException(SmartConfigFileException::ERR_CONFIG_NOT_LOADED);
        }
    }
        
}

class SmartConfigFileException extends BaseException
{
    const ERR_CONFIG_NOT_FOUND  = 1;
    const ERR_REGEX_NOT_FOUND   = 2;
    const ERR_PLUGIN_NOT_FOUND  = 3;
    const ERR_ALREADY_LOADED    = 4;
    const ERR_PREVIOUS_ERROR    = 5;
    const ERR_CONFIG_NOT_LOADED = 6;
    const ERR_SAVING_CONFIG     = 7;
}
