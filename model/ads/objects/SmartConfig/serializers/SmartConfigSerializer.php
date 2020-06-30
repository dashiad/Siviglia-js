<?php

namespace model\ads\SmartConfig\serializers;

use lib\storage\TypeSerializer;
use model\ads\SmartConfig\serializers\SmartConfig\storage\SmartConfigException;
use lib\model\types\DataSet;
use model\ads\SmartConfig;
use model\reflection\Model;
use model\ads\SmartConfig\Definition;
use lib\php\ParametrizableString;
use model\ads\lib\SmartConfigFile\SmartConfigFileException;
use model\ads\SmartConfig\serializers\storage\QueryBuilder;

require_once(__DIR__.'/SmartConfigDataSource.php');
require_once(__DIR__.'/storage/SmartConfig.php');
require_once(__DIR__.'/storage/QueryBuilder.php');


class SmartConfigSerializerException extends \lib\model\BaseException
{
    const ERR_FILE_NOT_FOUND  = 1;
    const ERR_PATH_NOT_FOUND  = 2;
    const ERR_BAD_FILE_FORMAT = 3;
}

class SmartConfigSerializer extends \lib\storage\StorageSerializer
{
    
    const SMARTCONFIG_SERIALIZER_TYPE = "smartconfig";
    
    protected $entryPoint;
    
    public function __construct($definition, $serType=null) 
    {
        parent::__construct($definition, $serType);
        $this->serializerType = self::SMARTCONFIG_SERIALIZER_TYPE;
        // TODO: leer ruta a entrypoint de configuración
        $this->entryPoint = "http://cdn.smartclip-services.com/v1/Storage-a482323/smartclip-services/ava/config/editor/entryPoint.php";
        $this->storageManager = new \model\ads\SmartConfig\serializers\SmartConfig\storage\SmartConfig($definition);
    }
    
    public function next()
    {}

    public function unserialize($object, $queryDef = null, $filterValues = null)
    {
        $object->__setSerializer($this);
        foreach ($queryDef["CONDITIONS"] as $filter) {
            $params[$filter["FILTER"]["F"]] = $filter["FILTER"]["V"];
        }
        
        $qb = $this->getQueryBuilder($this->definition, $queryDef);
        $q = $qb->build($params, true);
        $result = $this->storageManager->request($q);
        $config = $this->parseConfig($result);
        
//         // busca regex
//         foreach ($config['actions'] as $value) {
//             if ($params['regex']==$value['regex'][0]) {
//                 $regex=$value;
//                 break;
//             }
//         }
//         // busca el plugin
//         if (isset($regex['actions'][$params['plugin']])) {
//             $data = $regex['actions'][$params['plugin']];
//         }
        
//         if (is_null($data) || is_null($regex)) {
//             throw new SmartConfigSerializerException(SmartConfigSerializerException::ERR_PATH_NOT_FOUND);
//         }
        
//         $object->config = [$params['plugin'] => $data];

//          var_dump($config);

//          var_dump($object);
        $object->config = $config['config']; 
        
    }

    public function fetchCursor($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    {}
    
    public function getPagingParameters()
    {
        
    }
    public function destroyDataSpace($spaceDef)
    {}

    public function fetchAll($queryDef, &$data, &$nRows, &$matchingRows, $params, $pagingParams)
    { 
         $q = $this->buildQuery($queryDef, $params, $pagingParams);
         
         $this->storageManager->setUrl($q['url']);
         $this->storageManager->setMethod($q['method']);
         $result = $this->storageManager->request($q);
         
         if ($queryDef["BASE"]["action"]!="getFolderContent") {
         
             $config = $this->parseConfig($result);
             
             if (!empty($params['regex'])) { // devuelve solo las regex solicitadas
                 foreach($config['config'] as $regex=>$plugins) {
                     if (!in_array($regex, $params['regex'])) {
                         unset($config['config'][$regex]);
                     }
                 }
             }
             if (!empty($params['plugin'])) {
                 foreach($config['config'] as $regex=>$plugins) {
                     foreach($plugins as $plugin=>$value) {
                         if (!in_array($plugin, $params['plugin'])) {
                             unset($config['config'][$regex][$plugin]);
                         }
                     }
                 }
             }                 
             
             $this->__returnedFields = array_keys($config);
             $this->data = $config;
             $this->nRows = 1;
         
         } else {
             $this->__returnedFields = ["domain"];
             $this->data = [];
             foreach (explode(PHP_EOL, $result) as $filename) {
                 if (!empty(trim($filename))) {
                     $domain = explode(".", $filename);
                     $this->data[] = ["domain" => $domain[0]];
                 }
             }
             $this->nRows = count($this->data); 
         }
         
         
         $this->iterator = new \lib\model\types\DataSet(["FIELDS"=>$this->__returnedFields], $this->data, $this->nRows, $this->matchingRows, $this, $this->mapField);
         $this->loaded = true;
         return $this->iterator;
    }
    
    protected function readConfig(Array $q)
    {
        if ($this->checkDomain("")) {
            $url = $this->entryPoint."?action=getFileContent&file=$domain.js";
            $method = "GET";
            $result = "";
        } else {
            throw new SmartConfigSerializerException(\model\ads\SmartConfig\serializers\SmartConfigSerializerException::ERR_FILE_NOT_FOUND);
        }
    }
    
    protected function parseConfig(String $data) : ?Array
    {

        if ($data===FALSE) {
            throw new SmartConfigSerializerException(SmartConfigSerializerException::ERR_FILE_NOT_FOUND); 
        } else {
            $matches = null;
            if (preg_match('/SMC\.Config\.process\(([\s\S]*?)\)\;/', $data, $matches) == 1) {
            //if (preg_match('/Site_conf=([\s\S]*?)$/', $data, $matches) == 1) {
                
                // eliminamos comentarios
                $result = preg_replace('!/\*.*?\*/!s', '', $matches[1]);
                $result = preg_replace('/\n\s*\n/', "\n", $result);
                // reemplazamos comillas simples por dobles
                $result = str_replace("'", '"', $result);
                // eliminamos comas al final de listas
                $result = preg_replace("/,([\s]*[\]}][\s]*)/","$1", $result);
                // ahora tenemos un json que puede parsearse a un array PHP
                $result = json_decode($result, true);
                if (json_last_error()!=FALSE) {
                    throw new SmartConfigSerializerException(SmartConfigSerializerException::ERR_BAD_FILE_FORMAT);
                } else {
                    $config = [
                        'configType' => $result['configType'],
                        'domain' => $result['domain'],
                        'config' => [],
                    ];
                    
                    foreach ($result['actions'] as $action)
                    {
                        $config['config'][$action['regex'][0]] = $action['actions'];
                    }                    
                    return $config;
                }
            } else {
                throw new SmartConfigSerializerException(SmartConfigSerializerException::ERR_BAD_FILE_FORMAT);
            }
        }
    }

    protected function writeConfig(Array $q)
    {
        $dataTemplate = "SMC.Config.process([%config]);";
        $config = ['config' => json_encode($q)];
        // TODO: mezclar configuración con la almacenada (puede actualizarse solo la de un plugin)
        $url = $this->entryPoint . "?action=changeFileContent&file=$domain.js&content=".rawurlencode(ParametrizableString::getParametrizedString($dataTemplate, $config));
        return $url;
    }
   
    protected function checkDomain(String $domain) 
    {
        return true;
        $url = $this->entryPoint."?action=getFolderContent";
        $method = "GET";
        
        $result = ""; // TODO: llamar api
        return preg_match("$domain.js", $result)===1;        
    }
    
   
    public function updateFromAssociative($target, $fields, $query)
    {
        //
    }
    
    public function createStorage($modelDef, $extraDef = null)
    {}

    public function count($definition, &$model)
    {}

    public function getQueryBuilder($definition, $params)
    {        
        return new QueryBuilder($this, $this->definition);
    }

    public function insertFromAssociative($target, $data)
    {
        //
    }

    public function buildQuery($definition, $parameters, $pagingParameters, $getRows = true)
    {
        $qB = new storage\QueryBuilder($this, $definition, $parameters, $pagingParameters);
        $qB->findFoundRows($findRows);
        $query = [
            'definition' => $definition["BASE"],
            'parameters' => $parameters,
        ];
        return  $qB->build($query);
    }

    public function existsDataSpace($name)
    {}

    public function useDataSpace($name)
    {}

    public function deleteByQuery($q, $params = null)
    {}

    public function createDataSpace($spaceDef)
    {}

    public function getTypeNamespace()
    {
        return __NAMESPACE__ .'\\types';
    }

    public function subLoad($definition, &$relationColumn)
    {}

    public function destroyStorage($object)
    {}

    //
}