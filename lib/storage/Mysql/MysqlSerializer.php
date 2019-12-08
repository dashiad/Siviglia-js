<?php

namespace lib\storage\Mysql;

class MysqlSerializerException extends \lib\model\BaseException
{


    const ERR_NO_CONNECTION_DETAILS = 2;
    const ERR_MULTIPLE_JOIN = 3;
    const ERR_NO_SUCH_OBJECT = 4;

}

class MysqlSerializer extends \lib\storage\StorageSerializer
{

    var $conn;
    var $currentDataSpace;
    var $storageManager;
    const MYSQL_SERIALIZER_TYPE="MYSQL";
    function __construct($definition,$useDataSpace=true)
    {

        if (!isset($definition["ADDRESS"]))
            throw new MysqlSerializerException(MysqlSerializerException::ERR_NO_CONNECTION_DETAILS);
        $this->storageManager = new Mysql($definition["ADDRESS"]);
        $this->conn = $this->storageManager;
        $this->conn->connect();
        if($useDataSpace)
            $this->useDataSpace($definition["ADDRESS"]["database"]);

        \lib\storage\StorageSerializer::__construct($definition, MysqlSerializer::MYSQL_SERIALIZER_TYPE);

    }

    function unserialize($object, $queryDef = null, $filterValues = null)
    {
        $object->__setSerializer($this);
        if ($queryDef)
        {
 //           $object->__setSerializerFilters("MYSQL",array("DEF" => $queryDef, "VALS" => $filterValues));
            //$object->filters["MYSQL"] = array("DEF" => $queryDef, "VALS" => $filterValues);
            $table=isset($queryDef["TABLE"])?$queryDef["TABLE"]:$object->__getTableName();
            $queryDef["BASE"] = "SELECT * FROM " . $table;
            if(isset($queryDef["CONDITIONS"]))
            {
                $condKeys=array_keys($queryDef["CONDITIONS"]);
                $queryDef["BASE"].=" WHERE [%".implode("%] AND [%",$condKeys)."%]";
            }
        }
        else
        {
            //$q="SELECT * FROM ".$object->__getTableName()." WHERE ";
            $queryDef = array("BASE" => array("*"),
                "TABLE" => $object->__getTableName(),
                "CONDITIONS" => $this->getIndexExpression($object));

            if (!$object->__getKeys())
                return false;
            $filterValues = null;
        }
        $qb = new QueryBuilder($this,$queryDef, $filterValues);
        $q = $qb->build();

        $arr = $this->conn->select($q);

        if (isset($arr[0]))
        {
            $this->unserializeObjectFromData($object,$arr[0]);
        }
        else
            throw new MysqlSerializerException(MysqlSerializerException::ERR_NO_SUCH_OBJECT, array("MODEL" => $queryDef["TABLE"]));
    }


    function delete($objects,$basedOnFields=null,$tableName=null)
    {
        if($objects!=null && !is_array($objects))
            $objects=[$objects];

        if(count($objects)==0)
            return;
        if($basedOnFields==null)
            $basedOnFields=$objects[0]->getIndexFields();
        $table=$tableName!=null?$tableName:$objects[0]->__getTableName();

        // TODO : Permitir tipos multiples aqui.
        $keys=[];
        $types=[];

        foreach($basedOnFields as $key=>$value)
        {
            $keys[]=$value;
            $types[$value]=$this->getTypeSerializer($objects[0]->{"*$value"});
        }
        $ser = $this;
        // Solo es 1 campo. Podemos hacer un operador "in"
        if(count($keys)==1) {
            $typeSerializer = $types[$keys[0]];
            $vals = [];

            $serializedValues = array_map(function ($item) use ($typeSerializer, $ser,$keys) {
                return $typeSerializer->serialize($keys[0],$item->{"*".$keys[0]}, $ser);
            },$objects);

            foreach($serializedValues as $k=>$v)
            {
                $vals[]=$v[$keys[0]];
            }
            $q = $keys[0]." IN (".implode(",",$vals).")";
        }
        else
        {

            // Si es mas de un campo, la query tiene que ser del tipo (a=b && c=d) || (a=b1 && c=d1) || ...
            $subParts=array_map(function($item) use ($keys,$types,$ser){
                $results=[];
                array_map(function($key) use ($types,$item,$ser,& $results){
                    $serialized=$types[$key]->serialize($key,$item->{"*".$key},$ser);
                    foreach($serialized as $k1=>$v1)
                    {
                        $results[]=$k1."=".$v1;
                    }
                },$keys);
                return "(".implode(" AND ",$results).")";
            },$objects);
            $q=implode(" OR ",$subParts);
        }
        $deleteQuery="DELETE FROM ".$table." WHERE ".$q;
        $this->conn->doQ($deleteQuery);
    }

    function deleteByQuery($q,$params=null)
    {
        $qb=$this->getQueryBuilder($q,$params);
        $q=$qb->build();
        $this->conn->delete($q);

    }
    function add($objects, $tableName=null)
    {

        if(!is_array($objects))
            $objects=[$objects];
        if(count($objects)==0)
            return;
        $table=$tableName==null?$objects[0]->__getTableName():$tableName;

        $nItems=count($objects);
        // En ES no existe campo Autoincrement, pero el objeto puede tener indices. Se busca un campo AutoIncrement, que
        // va a ser sustituido por un UUID.
        if($nItems == 0)
            return;

        $typeSerializers=$this->getSerializersForObject($objects[0]);

        $indexField=null;
        $func=null;
        $ser=$this;
        $keyObj = $objects[0]->__getKeys();


        $func=function($item) use ($indexField,$typeSerializers,$ser){
            $data=[];
            foreach($typeSerializers as $k=>$v) {
                $serialized=$typeSerializers[$k]->serialize($k, $item->{"*" . $k}, $ser);
                if($serialized!==null)
                    $data=array_merge($data,$serialized);
            }
            return $data;
        };

        $rows=array_map($func,$objects);

        $this->conn->insertFromAssociative($table,$rows);
    }
    function update($object,$byFields=[],$tableName=null)
    {
        $table=$tableName==null?$object->__getTableName():$tableName;
        $dirty=$object->getDirtyFields();
        $keys=$object->__getKeys();
        if(!$keys)
        {
            // TODO : Utilizar los campos que esten set, pero no "dirty"
        }
        $typeSerializers=$this->getSerializersForObject($object);
        $q = "UPDATE $table SET ";
        foreach ($dirty as $key => $value)
        {
            // TODO : Eliminar el mysql_escape_string, cambiarlo por serializado
            $serialized=$typeSerializers[$key]->serialize($key, $object->{"*" . $key}, $this);
            foreach($serialized as $k1=>$v1)
                $parts[] = $k1 . "=" . $v1 ;
        }
        $q.=(implode(",", $parts) . " WHERE ");
        $parts = array();

        foreach ($keys->indexFields as $key => $value)
        {
            $serialized=$typeSerializers[$key]->serialize($key, $object->{"*" . $key}, $this);
            foreach($serialized as $key=>$value)
                $parts[] = $key . "=" . $value;
        }
        $q.=implode(" AND ", $parts);
        $this->conn->update($q);
    }
/*
    function update($table, $keyValues, $fields)
    {
        $q = "UPDATE $table SET ";
        foreach ($fields as $key => $value)
        {
            // TODO : Eliminar el mysql_escape_string, cambiarlo por serializado
            $parts[] = $key . "='" . mysql_escape_string($value) . "'";
        }
        $q.=(implode(",", $parts) . " WHERE ");
        $parts = array();
        foreach ($keyValues as $key => $value)
        {
            $parts[] = $key . "='" . mysql_escape_string($value) . "'";
        }
        $q.=implode(" AND ", $parts);
        $this->conn->update($q);
    }*/
    // El primer parametro es la tabla
    // El segundo, es un array asociativo de tipo {clave_fija=>valor}.Son las columnas que indican la parte de la relacion fija, con su valor.
    // El tercero, es un array simple que indican los nombres de campo de la parte de relacion que estamos editando.
    // El cuarto, es un array con los valores a establecer.Este array es asociativo, y dentro de cada key, hay un array de valores.
    function setRelation($table,$fixedSide,$variableSides,$srcValues)
    {
        // Se tiene que crear una query de "DELETE" y otra de "INSERT IGNORE"
        $q="DELETE FROM ".$table." WHERE ";
        $n=0;
        foreach($fixedSide as $key=>$value)
        {
            if($n>0)$q.=" AND ";
            $q.=$key."=".$value;
        }
        if(count($variableSides)==1)
        {
            $variableSideName=$variableSides[0];
            if(count($srcValues[$variableSideName])>0)
                $q.=" AND ".$variableSides[0]." NOT IN (".implode(",",$srcValues[$variableSideName]).')';
        }
        else
        {
            // TODO : Para relaciones multiples donde la relacion con uno de los objetos, es a traves de mas de 1 campo.
        }
        $this->conn->doQ($q);
        $k=0;
        if(!$srcValues)
            return;
        $keys=array_keys($srcValues);
        $insExpr="INSERT IGNORE INTO ".$table." (".implode(",",$keys).") VALUES ";
        $doInsert=false;
        while(isset($srcValues[$variableSideName][$k]))
        {
            $parts=array();
            foreach($keys as $value)
            {
                $parts[]=$srcValues[$value][$k];
            }
            $insExpr.=($k>0?",":"")."(".implode(",",$parts).")";
            $k++;
            $doInsert=true;
        }
        if($doInsert)
            $this->conn->doQ($insExpr);
    }
    function subLoad($definition, & $relationColumn)
    {
        $objectName = $relationColumn->getRemoteObject();
        $builder = new QueryBuilder($this,$definition);
        $q = $builder->build();
        $results = $this->conn->select($q);
        $nResults = count($results);

        $models = array();
        for ($k = 0; $k < $nResults; $k++)
        {
            $newInstance=new $objectName();

            $newInstance->__setSerializer($this);
            $this->unserializeObjectFromData($newInstance,$results[$k]);
            $normalized=\lib\model\ModelCache::store($newInstance);
            $models[] = $normalized;
        }

        return $models;
    }

    function count($definition, & $model,$table=null)
    {
        $definition["BASE"] = array("COUNT(*) AS NELEMS");
        if($table!==null)
            $definition["TABLE"]=$table;
        $builder = new QueryBuilder($this,$definition,[]);
        $q = $builder->build();
        $result = $this->conn->select($q);
        return $result[0]["NELEMS"];
    }

    function createStorage($modelDef, $extraDef = null,$tableName=null)
    {
        if (!$extraDef)
        {
            $mysqlDesc = \model\reflection\Storage\Mysql\MysqlOptionsDefinition::createDefault($modelDef);
            $extraDef = $mysqlDesc->getDefinition();
        }
        $extraDefinition = $extraDef;
        $definition = $modelDef->getDefinition();

        if (isset($extraDefinition["FIELDS"]))
            $fields = array_merge($definition["FIELDS"], $extraDefinition["FIELDS"]);
        else
            $fields = $definition["FIELDS"];

        // Los objetos privados tienen como prefijo el objeto publico.
        $tableName = $tableName!==null?$tableName:str_replace('\\','_',$modelDef->getTableName());
        $fields = $modelDef->__getFields();

        $keys = (array) (isset($extraDefinition["KEY"]) ? $extraDefinition["KEY"] : $definition["INDEXFIELDS"]);

        $indexes = array_merge($keys, (array) (isset($extraDefinition["INDEXES"])?$extraDefinition["INDEXES"]:array()));
        if (!$indexes)
            $indexes = array();


        include_once(LIBPATH . "/php/ArrayTools.php");
        foreach ($fields as $key => $value)
        {

            $type = $value->getType();
            $serializers = array();
            $serType = $this->getSerializerType();


            $serializers[$key] = $this->getTypeSerializer($type);


            $def = $value->getDefinition();

            if (isset($def["REQUIRED"]) && $def["REQUIRED"])
                $notNullExpr = " NOT NULL";
            else
                $notNullExpr = "";

            $columnDef = $serializers[$key]->getSQLDefinition($key, $type->getDefinition(),$this);

            if (\lib\php\ArrayTools::isAssociative($columnDef))
                $columnDef = array($columnDef);

            for ($k = 0; $k < count($columnDef); $k++)
            {
                $fieldColumns[$key][] = $columnDef[$k]["NAME"];
                $sqlFields[] = "`" . $columnDef[$k]["NAME"] . "` " . $columnDef[$k]["TYPE"] . " " . $notNullExpr;
            }
        }
        $tableOptionsText = "";
        if ($extraDefinition["TABLE_OPTIONS"])
        {

            foreach ($extraDefinition["TABLE_OPTIONS"] as $key => $value)
                $tableOptionsText.=" $key $value";
        }
        $engine = $extraDefinition["ENGINE"];
        if (!$engine)
            $engine = "InnoDB";

        $pKey = (array) $definition["INDEXFIELDS"];
        if ($pKey)
            $pKeyCad = " PRIMARY KEY (`" . implode("`,`", $pKey) . "`)";

        $extraIndexes = null;
        if (isset($extraDefinition["INDEXES"]))
        {
            $extraIndexes = array();
            for ($k = 0; $k < count($extraDefinition["INDEXES"]); $k++)
            {
                $curIndex = $extraDefinition["INDEXES"][$k];
                $indexFields = $curIndex["FIELDS"];


                $isUnique = ($curIndex["UNIQUE"] && $curIndex["UNIQUE"]!="false") ? "UNIQUE " : "";
                $isFullText = $curIndex["FULLTEXT"] ? "FULLTEXT " : "";
                $indexType = $curIndex["TYPE"];
                $extraIndexes[] = $isFullText . $isUnique . " KEY " . $tableName . "_i" . $k . " (" . implode(",", $indexFields) . ")";
            }
        }


        $createTableQuery = "CREATE TABLE " . $tableName . " (" . implode(",", $sqlFields);
        $createTableQuery.=($pKeyCad ? "," . $pKeyCad : "") . ($extraIndexes ? "," . implode(",", $extraIndexes) : "");
        $createTableQuery.=")";


        $collation = $extraDefinition["COLLATE"];
        if (!$collation)
            $collation = "utf8_general_ci";
        $characterSet = $extraDefinition["CHARACTER SET"];
        if (!$characterSet)
            $characterSet = "utf8";

        $createTableQuery.="DEFAULT CHARACTER SET " . $characterSet . " COLLATE " . $collation;
        $createTableQuery.=" ENGINE " . $engine;
        //echo $createTableQuery."<br>";
        $this->conn->update($createTableQuery);
    }

    function destroyStorage($object,$tableName=null)
    {
        $table=$tableName!=null?$tableName:$object->__getTableName();

        $q = "DROP TABLE ".$tableName;
        $this->conn->doQ($q);
    }

    function createDataSpace($spaceDef)
    {
        $q = "CREATE DATABASE IF NOT EXISTS " . $spaceDef["NAME"];
        $this->conn->update($q);
    }

    function existsDataSpace($spaceDef)
    {
        $q = "SHOW DATABASES";

        $res = $this->conn->select($q, "Database");

        $names = array_map("strtolower", array_keys($res));
        return in_array(strtolower($spaceDef["NAME"]), $names);
    }

    function destroyDataSpace($spaceDef)
    {
        $q = "DROP DATABASE IF EXISTS " . $spaceDef["NAME"];
        $this->conn->update($q);
        $this->currentDataSpace=null;
    }

    function useDataSpace($dataSpace)
    {
        if ($this->currentDataSpace != $dataSpace)
        {
            $this->conn->selectDb($dataSpace);
            $this->currentDataSpace = $dataSpace;
        }
    }
    function getCurrentDataSpace()
    {
        return $this->currentDataSpace;
    }

    function buildQuery($queryDef,$params,$pagingParams,$findRows=true)
    {
        $qB = new QueryBuilder($this,$queryDef, $params,$pagingParams);
        $qB->findFoundRows($findRows);
        return  $qB->build();

    }
    function fetchAll($queryDef, & $data, & $nRows, & $matchingRows, $params,$pagingParams)
    {
        if(isset($queryDef["PRE_QUERIES"]))
        {
            foreach($queryDef["PRE_QUERIES"] as $cq)
                $this->conn->doQ($cq);
        }
        $q=$this->buildQuery($queryDef,$params,$pagingParams);
        //echo $q."<br>";
        $data = $this->conn->selectAll($q, $nRows);

        $frows = $this->conn->select("SELECT FOUND_ROWS() AS NROWS");
        $matchingRows = $frows[0]["NROWS"];
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
    function getConnection()
    {
        return $this->conn;
    }

    function processAction($definition,$parameters)
    {
        $qB = new QueryBuilder($definition, $parameters);
        $q = $qB->build();
        $this->conn->doQ($q);
    }
    function getTypeNamespace()
    {
        return '\lib\storage\Mysql\types';
    }
    function getQueryBuilder($conds,$params)
    {
        return new QueryBuilder($this,$conds,$params);
    }
    function insertFromAssociative($target, $data)
    {
        return $this->getConnection()->insertFromAssociative($target,$data);
    }
    function updateFromAssociative($target, $fields, $query)
    {
        return $this->getConnection()->updateFromAssociative($target,$fields,$query,false);
    }
    function updateOnSaveFields($object,$setOnSaveFields, $isNew)
    {
        foreach($setOnSaveFields as $k=>$v)
        {
            if($isNew && is_a($v->getType(),'\lib\model\types\AutoIncrement'))
                $object->{$k}=$this->conn->lastId();
            else {
                if(!$v->is_set())
                $pending[] = $k;
            }
        }
        // TODO : Update de otros tipos que sean SetOnSave, ademas del autoincrement, como los timestamps.
        // Para eso, habria que deserializar el objeto entero de nuevo.

    }
}




?>
