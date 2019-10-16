<?php

namespace lib\storage\ES;

use lib\storage\StorageSerializerException;

/**
 * Class ESSerializerException
 * @package lib\storage\ES
 *  (c) Smartclip
 * Excepciones lanzadas por el serializador de ES
 */
include_once(PROJECTPATH."/lib/storage/StorageSerializer.php");
class ESSerializerException extends \lib\storage\StorageSerializerException
{
    const ERR_UNSUPPORTED=101;
    const TXT_UNSUPPORTED="Operacion no soportada por ESSerializer :[%operation%]";
    const ERR_MISSING_INDEX_NAME=102;
    const TXT_MISSING_INDEX_NAME="Indice no especificado";
}

/**
 * Class ESSerializer
 * @package lib\storage\ES
 *  (c) Smartclip
 * Implementa el interfaz StorageSerializer para ElasticSearch.
 * Nota : Este serializador, implementa el uso de un campo "__type__" para declarar el tipo de objeto almacenado.
 * Esto es equivalente a poner un nombre de tabla.
 */
class ESSerializer extends \lib\storage\StorageSerializer
{

    var $conn;
    var $storageManager;
    var $currentIndex;
    const ES_SERIALIZER_TYPE="ES";
    /**
     * ESSerializer constructor.
     * @param $definition
     * @param bool $useDataSpace
     * La definicion contiene:
     * "HOSTS":[] array de ips del cluster.
     * "INDEX": especificacion de indices.
     */
    function __construct($definition,$useDataSpace=true)
    {
        \lib\storage\StorageSerializer::__construct($definition,"ES");
        if (!isset($this->innerDefinition["servers"]))
            throw new ESSerializerException(StorageSerializerException::ERR_NO_CONNECTION_DETAILS);


        $this->currentIndex=isset($this->innerDefinition["index"])?$this->innerDefinition["index"]:null;

    }

    function getConnection($model=null,$indexName=null)
    {
        $esOptions=null;
        if($indexName===null) {


            if (is_a($model, '\lib\model\BaseModel')) {
                $indexName = str_replace('\\', '_', $model->__getTableName());
                $esOptions = $model->__getSerializerOptions($this->serializerType);
            } else {
                $indexName = $this->currentIndex;
                $esOptions = isset($this->inner["OPTIONS"])?$this->innerDefinition["OPTIONS"]:null;
            }

            if ($esOptions != null) {
                if (isset($esOptions["PARTITION"])) {
                    switch ($esOptions["PARTITION"]) {
                        case "DAY":
                            {
                                $indexName .= "_" . \lib\model\types\Date::getValueFromTimestamp();
                            }
                            break;
                    }
                }
            }
        }
        if($indexName!=$this->currentIndex || $this->conn==null) {
            $this->currentIndex = $indexName;
            $this->conn=new ESClient(["servers"=>$this->innerDefinition["servers"],"port"=>$this->innerDefinition["port"],"index"=>$this->currentIndex]);
        }
        return $this->conn;
    }
    function unserialize($object, $queryDef = null, $filterValues = null,$index=null)
    {
        $conn=$this->getConnection($object,$index);
        if(is_a($object,'\lib\model\BaseModel')) {
            $object->__setSerializer($this);
            $object->__setSerializerFilters("ES", array("DEF" => $queryDef, "VALS" => $filterValues));
        }
        $def=array(
                "INDEX"=>$this->innerDefinition["index"],
                "CONDITIONS"=>[]
        );
        if($queryDef) {
            if (isset($def["CONDITIONS"])) {
                $def["CONDITIONS"] = array_merge($def["CONDITIONS"], $queryDef["CONDITIONS"]);
            }
        }
        else
        {
            $def["CONDITIONS"]=$this->getIndexExpression($object);
        }

        $qb = new QueryBuilder($this,$def, $object);
        $q = $qb->build();

        $arr = $conn->select($q);

        if (isset($arr["hits"]) && $arr["hits"]["total"]["value"]==1 && isset($arr["hits"]["hits"][0]))
        {
            $object->loadFromArray($arr["hits"]["hits"][0]["_source"],$this);

        }
        else
            throw new ESSerializerException(\lib\storage\StorageSerializerException::ERR_NO_SUCH_OBJECT, array("model" => $this->currentIndex));
    }
    // La funcion delete se basa en un array de definedObjects (puede ser uno)
    // y una lista de campos en los que basar el delete. Si la lista de campos es nula, se utilizan los campos indice de los definedObjects.
    // Si no, se basa en los valores especificos de cada campo.
    function delete($objects,$basedOnFields=null,$index=null)
    {

        if($objects!=null && !is_array($objects))
            $objects=[$objects];

        if(count($objects)==0)
            return;
        $conn=$this->getConnection($objects[0],$index);
        if($basedOnFields==null)
            $basedOnFields=$objects[0]->getIndexFields();

        $typeSerializers=$this->getSerializersForObject($objects[0]);
        // TODO : Permitir tipos multiples aqui.
        $keys=$basedOnFields;
        $types=array_values($typeSerializers);
        $ser = $this;
        // Solo es 1 campo. Podemos hacer un operador "in"
        if(count($keys)==1) {
            $typeSerializer = $types[0];

            $serializedValues = array_map(function ($item) use ($typeSerializer, $ser,$keys) {
                $serialized=$typeSerializer->serialize($keys[0],$item->{"*".$keys[0]}, $ser);
                return $serialized[$keys[0]];
            },$objects);
            $q = [
                "bool" => [
                    "filter" => [
                        "terms" => [
                            $basedOnFields[0] => $serializedValues
                        ]
                    ]
                ]
            ];
        }
        else
        {

            // Si es mas de un campo, la query tiene que ser del tipo (a=b && c=d) || (a=b1 && c=d1) || ...
            $subParts=array_map(function($item) use ($keys,$types,$ser){
                $result=[];

                array_map(function($item,$key,$type) use (& $result,$ser) {
                    $result[$key]=$type->unserialize($key,$item->getValue(),$ser);
                },$item,$keys,$types);
                return ["bool"=>["must"=>["term"=>[$result]]]];
            },$objects);
            $q=[
                "bool"=>[
                    "should"=>$subParts
                ]
            ];
        }

        $this->conn->delete($q,$this->currentIndex);
    }

    function deleteByQuery($query,$params=null)
    {

        $qb=$this->getQueryBuilder($query,$params);
        $def=$qb->build();
        $this->conn->delete($def);
    }

    function add($objects,$index=null)
    {
        if(!is_array($objects))
            $objects=[$objects];

        $nItems=count($objects);
        // En ES no existe campo Autoincrement, pero el objeto puede tener indices. Se busca un campo AutoIncrement, que
        // va a ser sustituido por un UUID.
        if($nItems == 0)
            return;
        $conn=$this->getConnection($objects[0],$index);

        $typeSerializers=$this->getSerializersForObject($objects[0]);

        $indexField=null;
        $func=null;
        $ser=$this;
        $keyObj = $objects[0]->__getKeys();
        if($keyObj!==null) {
            $indexField = $keyObj->getKeyNames();
            if (count($indexField) == 1)
                $indexField=$indexField[0];
            else
                $indexField=null;
        }

        $func=function($item) use ($indexField,$typeSerializers,$ser){
            $data=[];
                foreach($typeSerializers as $k=>$v) {
                    $data=array_merge($data,$typeSerializers[$k]->serialize($k, $item->{"*" . $k}, $ser));
                }
                if($indexField)
                $data["_id"]=$data[$indexField];
                return $data;
            };
        $rows=array_map($func,$objects);

        $conn->insertBulk($rows);
    }

    function update($objects, $fields=null,$index=null)
    {
        if(!is_array($objects))
            $objects=[$objects];
        $nItems=count($objects);
        // En ES no existe campo Autoincrement, pero el objeto puede tener indices. Se busca un campo AutoIncrement, que
        // va a ser sustituido por un UUID.
        if($nItems == 0)
            return;


        $conn=$this->getConnection($objects[0],$index);
        $typeSerializers=$this->getSerializersForObject($objects[0]);

        $indexField=null;
        $func=null;
        $ser=$this;
        $keyObj = $objects[0]->__getKeys();
        if($keyObj!==null) {
            $indexField = $keyObj->getKeyNames();
            if (count($indexField) == 1) {
                $idx=$indexField[0];
                $func = function ($item) use ($idx,$fields,$typeSerializers,$conn){
                    if($fields==null)
                        $fields=$item->getDirtyFields();
                    $data=[];
                    foreach ($fields as $k => $v) {
                        if($k!==$idx) {
                            $serKeys = $typeSerializers[$k]->serialize($k, $item->{"*".$k}, $conn);
                            foreach($serKeys as $k1=>$v1)
                                $data[$k1]=$v1;
                        }
                    }

                    $serIdx=$typeSerializers[$idx]->serialize($idx,$item->{"*".$idx},$conn);
                    $conn->update($serIdx[$idx],$data);
                };
                }
            else
            {
                $func = function ($item) use ($indexField,$fields,$typeSerializers,$conn){
                    $queryPart=[];
                    foreach($indexField as $k=>$v)
                    {
                        $serX=$typeSerializers[$k]->serialize($k,$item->{$k},$conn);
                        foreach($serX as $k1=>$v1)
                            $queryPart[$k1]=$v1;
                    }
                    $data=[];
                    foreach ($fields as $k => $v) {
                        $data=array_merge($data, $typeSerializers[$k]->serialize($k, $item->{$k}, $conn));

                    }
                    $conn->conn->update(null,$data,["filtered"=>["filter"=>["bool"=>["must"=>["match"=>$queryPart]]]]]);
                };
            }
            array_map($func,$objects);
        }
    }
    // El primer parametro es la tabla
    // El segundo, es un array asociativo de tipo {clave_fija=>valor}.Son las columnas que indican la parte de la relacion fija, con su valor.
    // El tercero, es un array simple que indican los nombres de campo de la parte de relacion que estamos editando.
    // El cuarto, es un array con los valores a establecer.Este array es asociativo, y dentro de cada key, hay un array de valores.
    function setRelation($table,$fixedSide,$variableSides,$srcValues)
    {
        // No implementado.
        return;
    }

    function subLoad($definition, & $relationColumn)
    {
        $objectName = $relationColumn->getRemoteObject();
        $builder = new QueryBuilder($definition);
        $q = $builder->build();
        $results = $this->conn->select($q);
        $nResults = count($results);

        $models = array();
        for ($k = 0; $k < $nResults; $k++)
        {
            $newInstance=\lib\model\BaseModel::getModelInstance($objectName);
            $newInstance->__setSerializer($this);
            $newInstance->loadFromArray($results[$k], $this);
            $normalized=\lib\model\ModelCache::store($newInstance);
            $models[] = $normalized;
        }

        return $models;
    }

    function count($definition, & $model,$index=null)
    {
        $conn=$this->getConnection($model,$index);
        $q=null;
        if($definition!==null) {
            $builder = new QueryBuilder($definition);
            $q = $builder->build();
        }

        return $conn->getCount($q);
    }

    function createStorage($model, $extraDef = null,$index=null)
    {
        if (!$extraDef)
        {
            $esDesc = \model\reflection\Storage\ES\ESOptionsDefinition::createDefault($model);
            $extraDefinition = $esDesc->getDefinition();
        }
        $extraDefinition = $extraDef;

        $definition = $model->getDefinition();

        if (isset($extraDefinition["FIELDS"]))
            $fields = array_merge($definition["FIELDS"], $extraDefinition["FIELDS"]);
        else
            $fields = $definition["FIELDS"];

        $conn=$this->getConnection($model,$index);


        include_once(LIBPATH . "/php/ArrayTools.php");
        foreach ($fields as $key => $value)
        {
            $typeValue=\lib\model\types\TypeFactory::getType(null,$value);

                $typeSerializer = $this->getTypeSerializer($typeValue);

                $columnDef = $typeSerializer->getSQLDefinition($key, $value,$this);

                if (\lib\php\ArrayTools::isAssociative($columnDef)) {
                        $mapping[$columnDef["NAME"]]=$columnDef["TYPE"];
                }
                else {

                    for($j=0;$j<count($columnDef);$j++)
                        $mapping[$columnDef[$j]["NAME"]] = $columnDef[$j]["TYPE"];
                }
            }

        $options=null;
        if(is_a($model,'\lib\model\BaseModel')) {
            $indexOptions = $model->__getSerializerOptions($this->serializerType);
            if ($indexOptions && isset($indexOptions["INDEX"]))
                $options = $indexOptions["INDEX"];
        }

        $conn->createIndex($this->currentIndex,$mapping,null,$options);
    }

    function destroyStorage($object,$indexName=null)
    {
        $conn=$this->getConnection($object,$indexName);
        $this->conn->destroyIndex($this->currentIndex);
    }

    function createDataSpace($spaceDef)
    {
    }

    function existsDataSpace($spaceDef)
    {
     return true;
    }

    function destroyDataSpace($spaceDef)
    {

    }

    function useDataSpace($dataSpace)
    {

    }
    function getCurrentDataSpace()
    {
        return null;
    }

    function buildQuery($queryDef,$params,$pagingParams,$findRows=true)
    {
        $qB = new QueryBuilder($queryDef, $params,$pagingParams);
        $qB->findFoundRows($findRows);
        return  $qB->build();

    }
    function fetchAll($queryDef, & $data, & $nRows, & $matchingRows, $params,$pagingParams)
    {
        $q=$this->buildQuery($queryDef,$params,$pagingParams);
        //echo $q."<br>";
        $data=$this->conn->select($q);
        $data = $this->conn->selectAll($q, $nRows);
        return $data;
    }

    function fetchCursor($queryDef, & $data, & $nRows, & $matchingRows, $params,$pagingParams)
    {
        if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }
        $q=$this->buildQuery($queryDef,$params,$pagingParams,false);
        //echo $q."<br>";
        $this->currentCursor =  $this->conn->cursor($q);
        $nRows=0;
        $matchingRows=0;
    }

    function next()
    {
        if($this->currentCursor)
            return $this->conn->fetch($this->currentCursor);
        return null;
    }

    function processAction($definition,$parameters)
    {
        $qB = new QueryBuilder($definition, $parameters);
        $q = $qB->build();
        $this->conn->doQ($q);
    }
    function getTypeNamespace()
    {
        return '\lib\storage\ES\types';
    }

    function getQueryBuilder($conds,$params)
    {
        return new QueryBuilder($conds,$params);
    }

}

