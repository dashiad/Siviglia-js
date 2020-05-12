<?php
/**
 * Class PDOWriterCursor
 * @package lib\data\Cursor\PDO
 *  (c) Smartclip
 */


namespace lib\data\Cursor\Mysql;
use phpDocumentor\Reflection\Type;

include_once(LIBPATH."/data/Cursor/Cursor.php");
include_once(LIBPATH. "/storage/Mysql/Mysql.php");
include_once(LIBPATH."/model/types/TypeFactory.php");

/**
 * Class MysqlriterCursor
 * @package lib\data\Cursor\PDO
 *  (c) Smartclip
 *  Clase cursor de escritura en bases de datos PDO (aunque, sin otros cambios, solo iria bien en mysql).
 *  Como cursor, solo hace INSERTS. En caso de no existir la tabla destino, la creara, con los tipos definidos segun
 *  lo que se especifique en a) la declaracion de tipos / b) el servicio de tipado (a desarrollar).
 *  Si usa un servicio de tipado, podria crear indices, etc, segun lo indicado en la extension PDO del tipo.
 */
class MysqlWriterCursor extends \lib\data\Cursor\Cursor
{
    /**
     * @param $params
     * Los parametros requeridos son connection y table:
     * "connection" es un array que contiene las keys:
     *      "string": Cadena de conexion PDO.
     *      "userName": usuario
     *      "password": password
     *      "database": database.
     *  "table": Tabla destino.
     *  "replace": true o false, si la tabla actual debe reemplazarse.Si es false, los registros se agregaran.Por defecto, se reemplaza
     *  "types":[] array con los tipos de datos de cada columna.
     *  "typeService":[ ] especificacion para el servicio de tipos. Datos de conexion, nombre del tipo, serializador a usar.
     *
     */
    function init($params)
    {
        parent::init($params);
        $conn=$params["connection"];
        $this->client=PDOFactory::getConnection($conn["string"],$conn["userName"],$conn["password"]);
        $this->database=$conn["database"];
        $this->table=$params["table"];
        $this->mode=isset($params["replace"])?($params["replace"]?"replace":"append"):"replace";
        $bulkSize=1;
        if(isset($params["bulkSize"]))
            $bulkSize=$params["bulkSize"];
        $me=$this;
        $currentData=[];
        // Se crean tipos, y serializadores para cada una de las columnas.
        $typeObjs=$this->getTypesAndSerializers();
        $this->checkTable();
        $params["callback"]=function($item)use (& $currentData,$me,$typeObjs,$bulkSize){
            $currentData=array_merge($currentData,$item);
            $n=count($currentData);
            if($n>$bulkSize) {
                $rows=[];
                for($k=0;$k<$n;$k++)
                {
                    $row=[];
                    $c=$currentData[$k];
                    foreach($typeObjs as $k=>$v)
                    {
                        $v["type"]->setValue($currentData[$k]);
                        $row[]=$v["serializer"]->serialize($v["type"]);
                    }
                    $rows[]="(".implode(",",$row).")";
                }
                $fullQuery="INSERT INTO ".$this->table." (".implode(",",array_keys($typeObjs)).") VALUES ".implode(",",$rows);
                $stmt = $me->client->prepare($fullQuery);
                $me->client->beginTransaction();
                $stmt->execute();
                $me->client->commit();
                $currentData=[];
            }
            return $item;
        };

        $params["endCallback"]=function() use (& $currentData,$me){
            if(count($currentData)>0) {
                $me->client->insertBulk($me->indexName, $me->docType, $currentData);
            }
        };

    }

    /**
     * @return array
     * @throws \lib\model\types\BaseTypeException
     * Obtiene los tipos y serializadores de los tipos
     */
    function getTypesAndSerializers()
    {
        if(isset($this->params["types"]))
        {
            $result=[];
            foreach($this->params["types"] as $key=>$value)
            {
                $t=\lib\model\types\TypeFactory::getType($key,$value,null,null,\lib\model\types\BaseType::VALIDATION_MODE_NONE);
                $s=\lib\model\types\TypeFactory::getSerializer($t,\lib\storage\Mysql\MysqlSerializer::MYSQL_SERIALIZER_TYPE);
                $result[$key]=["type"=>$t,"serializer"=>$s];
            }
            return $result;
        }
    }
    function checkTable()
    {
        $tableExists=false;
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $result = $this->client->query("SELECT 1 FROM ".$this->table." LIMIT 1");
            $tableExists=true;
        } catch (\Exception $e) {
            $sers=$this->getTypesAndSerializers();
        }
        if($tableExists)
        {
            //if($params[])
        }
    }
}
