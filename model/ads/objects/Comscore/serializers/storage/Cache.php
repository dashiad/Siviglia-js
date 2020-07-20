<?php
namespace lib\storage\Cache;

use lib\php\ParametrizableString;
use lib\model\BaseTypedObject;

class CacheException extends \lib\model\BaseException
{
    const ERR_NO_SOURCES_DEFINED = 1;
    const ERR_SOURCE_NOT_DEFINED = 2;
    
    const TXT_NO_SOURCES_DEFINED = "Cache must define at least one storage";
    const TXT_SOURCE_NOT_DEFINED = "Storage [%source%] is not defined";
    
}

class Cache
{
    const KEY_VALUE_DEFINITION = [
        'PARAMS' => [
            'key' => [
                'TYPE' => 'String',
            ],
        ],
        'FIELDS' => [
            'key' => [
                'TYPE' => 'String',
            ],
            'value' => [
                'TYPE' => 'PHPVariable',
            ],
            'ttl' => [
                'TYPE' => 'DateTime',
            ],
        ],
        'CONDITIONS' => [
            [
                'FILTER' => [
                    'F'  => '`key`',
                    'OP' => '=',
                    'V'  => '[%key%]'
                ],
                'TRIGGER_VAR' => 'key',
                'DISABLE_IF'  => '0',
                'FILTERREF'   => 'key'
            ],
        ],
    ];
    
    protected $definition;
    protected $sources = [];
    protected $cache;
    protected $current = null;
    protected $updateUpstream = true;
    protected $ttl;
    
    
    
    /**
     * 
     * @param array $definition
     * @throws CacheException
     * 
     */
    public function __construct(Array $definition)
    {
        $this->definition = $definition;
        $this->cache = $definition['SOURCE']['STORAGE']['CACHE'];
        if (!empty($this->cache['SOURCES'])) {
            $this->sources = $this->cache['SOURCES'];
        } else {
            throw new CacheException(
                CacheException::ERR_NO_SOURCES_DEFINED, 
                CacheException::TXT_NO_SOURCES_DEFINED
            );
        }
    }
    
    /**
     * 
     * @param array $q
     * @return boolean|array
     */
    public function request(Array $q)
    {
        $source = $q['source'] ?? $this->getFirstSource();

        $index = $this->getIndexOfSource($source);
        $found = false;
        
        while(!$found && $index<$this->countSources()) {
            $source = ucfirst(strtolower($this->getSourceByIndex($index)));
            $serializer = \Registry::getService("storage")->getSerializerByName($source);
            $storage= $serializer->getConnection(); 
            
            if ($this->sources[$source]['KEY_VALUE'] ?? false) {
                $params = new BaseTypedObject(static::KEY_VALUE_DEFINITION);
                $params->key = $this->hashQuery($q);
            } else {
                $params = new BaseTypedObject($this->definition);
            }
            $query = $serializer->buildQuery($this->definition, $params, null, null);
            
            $result = $this->checkTTL($storage->query($query), $storage, $source);
            $found = !empty($result);
            
            $index++;
        }
        
        if ($found) $this->updateUpstream();
        return $result ?? false;
    }
    
    
    protected function hashQuery(Array $q) : String 
    {
        return md5(serialize($q));
    }
    
    protected function checkTTL(&$result, $storage, String $source)
    {
        if (!empty($result) && !$this->sourceCanManageTTL($source)) {
            // TODO: si el TTL ha expirado, borro la entrada y pongo $result a false
            
        }
        return $result;
    }
    
    /**
    * 
    * @param String $source
    * @throws CacheException
    * @return array
    * 
    */
    protected function selectSource(String $source) : Array
    {
        if (!isset($this->sources[$source])) {
            throw new CacheException(
                CacheException::ERR_SOURCE_NOT_DEFINED, ['source' => $source], $this);
        }
        $this->current = $source;
        return $this->sources[$source];
    }
    
    /**
     * 
     * @param Int $index
     * @throws CacheException
     * @return String
     */
    protected function getSourceByIndex(Int $index) : String
    {
        if ($index>$this->countSources())
            throw new CacheException(
                CacheException::ERR_SOURCE_NOT_DEFINED,
                ParametrizableString::getParametrizedString(
                    CacheException::TXT_SOURCE_NOT_DEFINED,
                    ['source' => "index: $index"]
                )
            );
        $sources = $this->getSourceKeys();
        return $sources[$index];
    }
    
    /**
     * 
     * @param String $source
     * @throws CacheException
     * @return Int
     */
    protected function getIndexOfSource(String $source) : Int
    {
        $sources = $this->getSourceKeys();
        $index = array_search($source, $sources);
        if ($index===FALSE)
            throw new CacheException(
                    CacheException::ERR_SOURCE_NOT_DEFINED,
                    ParametrizableString::getParametrizedString(
                        CacheException::TXT_SOURCE_NOT_DEFINED,
                        ['source' => $source]
                    )
                );
       return $index;
            
    }
    
    /**
     * 
     * @return array
     */
    protected function getSourceKeys() : Array
    {
        return array_keys($this->sources);
    }
    
    /**
     * 
     * @return String
     * 
     */
    protected function getDefaultSource() : String
    {
        return $this->getLastSource();
    }
    
    /**
     * 
     * @return String
     * 
     */
    protected function getFirstSource() : String
    {
        return $this->getSourceByIndex(0);
    }
    
    /**
     * 
     * @return String
     * 
     */
    protected function getLastSource() : String
    {
        return $this->getSourceByIndex($this->countSources()-1);
    }
    
    /**
     * 
     * @return Int
     * 
     */
    protected function countSources() : Int
    {
        return count($this->sources);
    }
    
    /**
     * 
     * @param String $source
     * @return bool
     */
    protected function sourceCanManageTTL(String $source) : bool
    {
        return $source['TTL_CAPABLE'] ?? false; // TODO: ruta exacta a TTL_CAPABLE
    }
    
    protected function updateUpstream()
    {
        if ($this->updateUpstream) {
            //for ($i=$this->current;$i>0;$i++) {}
        }
    }
}


