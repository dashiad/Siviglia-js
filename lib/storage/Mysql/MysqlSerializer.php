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
            $this->useDataSpace($definition["ADDRESS"]["database"]["NAME"]);

        \lib\storage\StorageSerializer::__construct($definition, MysqlSerializer::MYSQL_SERIALIZER_TYPE);

    }

    function unserialize($object, $queryDef = null, $filterValues = null)
    {
        $object->__setSerializer($this);
        if ($queryDef)
        {
            $object->__setSerializerFilters("MYSQL",array("DEF" => $queryDef, "VALS" => $filterValues));
            //$object->filters["MYSQL"] = array("DEF" => $queryDef, "VALS" => $filterValues);
            $queryDef["BASE"] = "SELECT * FROM " . $object->__getTableName();
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
        $qb = new QueryBuilder($queryDef, $filterValues);
        $q = $qb->build();

        $arr = $this->conn->select($q);

        if (isset($arr[0]))
        {
            $fieldList = $object->__getFields();
            foreach ($fieldList as $key => $value)
            {
                $value->unserialize($arr[0],"MYSQL");
            }
        }
        else
            throw new MysqlSerializerException(MysqlSerializerException::ERR_NO_SUCH_OBJECT, array("MODEL" => $object->__getTableName()));
    }


    function delete($objects,$basedOnFields=null)
    {
        if($objects!=null && !is_array($objects))
            $objects=[$objects];

        if(count($objects)==0)
            return;
        if($basedOnFields==null)
            $basedOnFields=$objects[0]->getIndexFields();

        // TODO : Permitir tipos multiples aqui.
        $keys=[];
        $types=[];

        foreach($basedOnFields as $key=>$value)
        {
            $keys[]=$key;
            $types[]=$this->getTypeSerializer($value->getType());
        }
        $ser = $this;
        // Solo es 1 campo. Podemos hacer un operador "in"
        if(count($keys)==1) {
            $typeSerializer = $types[0];
            $vals = [];

            $serializedValues = array_map(function ($item) use ($typeSerializer, $ser) {
                return $typeSerializer->unserialize($item->getValue(), $ser);
            },$objects);
            $q = $keys[0]." IN (".implode(",",$serializedValues).")";
        }
        else
        {

            // Si es mas de un campo, la query tiene que ser del tipo (a=b && c=d) || (a=b1 && c=d1) || ...
            $subParts=array_map(function($item) use ($keys,$types,$ser){

                $result=array_map(function($item,$key,$type) use (& $result,$ser) {
                    return $key."=".$type->unserialize($item->getValue(),$ser);
                },$item,$keys,$types);
                return "(".implode(" AND ",$result);
            },$objects);
            $q=implode(" OR ",$subParts);
        }
        $this->deleteByQuery($q);
    }

    function deleteByQuery($q,$params=null)
    {
        $qb=$this->getQueryBuilder($q,$params);
        $this->conn->delete($qb->build());

    }
    function add($table, $keyValues, $extraValues = null)
    {
        if (is_object($table))
            $table = $table->__getTableName();

        $q = "INSERT INTO $table ";
        $nVals = count($keyValues);
        $inserts = array();
        for ($k = 0; $k < $nVals; $k++)
        {

            $vals = array();
            foreach ($keyValues[$k] as $key => $value)
            {
                if ($k == 0)
                    $fieldNames[] = $key;

                $vals[] = $value;
            }
            $inserts[] = "(" . implode(",", $vals) . ")";
        }

        $q.="(" . implode(",", $fieldNames) . ") VALUES " . implode(",", $inserts);

        $this->conn->insert($q);
    }

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
    }
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

    function count($definition, & $model)
    {
        $definition["BASE"] = array("COUNT(*) AS NELEMS");
        $builder = new QueryBuilder($definition);
        $q = $builder->build();
        $result = $this->conn->select($q);
        return $result[0]["NELEMS"];
    }

    function createStorage($modelDef, $extraDef = null)
    {
        if (!$extraDef)
        {
            $mysqlDesc = \model\reflection\Storage\Mysql\ESOptionsDefinition::createDefault($modelDef);
            $extraDef = $mysqlDesc->getDefinition();
        }
        $extraDefinition = $extraDef;
        $definition = $modelDef->getDefinition();

        if (isset($extraDefinition["FIELDS"]))
            $fields = array_merge($definition["FIELDS"], $extraDefinition["FIELDS"]);
        else
            $fields = $definition["FIELDS"];

        // Los objetos privados tienen como prefijo el objeto publico.
        $tableName = str_replace('\\','_',$modelDef->getTableName());
        $fields = $modelDef->fields;

        $keys = (array) (isset($extraDefinition["KEY"]) ? $extraDefinition["KEY"] : $definition["INDEXFIELDS"]);

        $indexes = array_merge($keys, (array) (isset($extraDefinition["INDEXES"])?$extraDefinition["INDEXES"]:array()));
        if (!$indexes)
            $indexes = array();


        include_once(LIBPATH . "/php/ArrayTools.php");
        foreach ($fields as $key => $value)
        {

            $types = $value->getRawType();
            $serializers = array();
            $serType = $this->getSerializerType();

            foreach ($types as $typeKey => $typeValue)
            {
                $serializers[$typeKey] = \lib\model\types\TypeFactory::getSerializer($typeValue, $serType);
            }

            $def = $value->getDefinition();

            if (isset($def["REQUIRED"]) && $def["REQUIRED"])
                $notNullExpr = " NOT NULL";
            else
                $notNullExpr = "";

            foreach ($serializers as $type => $typeSerializer)
            {
                $columnDef = $typeSerializer->getSQLDefinition($type, $types[$type]->getDefinition(),$this);

                if (\lib\php\ArrayTools::isAssociative($columnDef))
                    $columnDef = array($columnDef);

                for ($k = 0; $k < count($columnDef); $k++)
                {
                    $fieldColumns[$key][] = $columnDef[$k]["NAME"];
                    $sqlFields[] = "`" . $columnDef[$k]["NAME"] . "` " . $columnDef[$k]["TYPE"] . " " . $notNullExpr;
                }
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

    function destroyStorage($object)
    {

        $instance = \lib\model\BaseModel::getModelInstance($object);
        $tableName = $instance->__getTableName();
        $q = "DROP TABLE " . $tableName;
        $this->conn->update($q);
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
        $qB = new QueryBuilder($queryDef, $params,$pagingParams);
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
        return new QueryBuilder($conds,$params);
    }
}



?>
