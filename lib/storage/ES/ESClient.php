<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 15/04/2019
 * Time: 19:05
 */

namespace lib\storage\ES;

use ElasticSearch;
use Elasticsearch\ClientBuilder;
use Exception;

class ESClientException extends \lib\model\BaseException
{
    const ERR_INSERT_ERROR=100;
    const TXT_INSERT_ERROR="Error de insercion: {%esError%}";
    const ERR_CREATE_INDEX_ERROR=101;
    const TXT_CREATE_INDEX_ERROR="Error creando el indice {%indexName%}";
    const ERR_DESTROY_INDEX_ERROR=102;
    const TXT_DESTROY_INDEX_ERROR="Error destruyendo el indice {%indexName%}:{%description%}";
}

class ESClient
{
    const DEFAULT_INDEX_SHARDS=2;
    const DEFAULT_INDEX_REPLICAS=1;
    var $client;
    var $config;

    function __construct($definition)
    {
        $servers=$definition["servers"];
        $port=isset($definition["port"])?$definition["port"]:9200;
        $s=[];
        foreach($servers as $key=>$value)
        {
            $s[]=[ 'host' => $value,
                'port' => $port==null?9200:$port,
                'scheme' => 'http'];
        }
        $this->index=$this->normalizeIndexName($definition["index"]);
        $this->config=$s;
    }

    function getConnection()
    {
        if($this->client===null)
            $this->client=\Elasticsearch\ClientBuilder::create()->setHosts($this->config)->build();
        return $this->client;
    }

    function query($q,$subs=null)
    {
        if(isset($q["index"]))
            $q["index"]=$this->normalizeIndexName($q["index"]);
        else
            $q["index"]=$this->index;
        $patterns=[];
        $client=$this->getConnection();
        if($subs!=null)
        {
            foreach($subs as $k=>$v)
                $patterns[]="%%$k%%";
            $q=str_replace($patterns,array_values($subs),$q);
        }
        return $client->search($q);
    }
    function update($key,$fields,$q=null,$isUpsert=false){
        $conf=[
            "index"=>$this->index,
            "id"=>$key,
            "body"=>[
                "doc"=>$fields
            ]
        ];
        if($key!==null)
            $conf["id"]=$key;
        if($q!=null)
            $conf["body"]["query"]=$q;
        $response=$this->getConnection()->update($conf);
    }


    function normalizeIndexName($indexName)
    {
        return strtolower($indexName);
    }
    function createIndex($indexName,$definition,$alias=null,$extraDef=[])
    {
        $indexName=$this->normalizeIndexName($indexName);
        $client=$this->getConnection();
        // TODO : Controlar y lanzar excepciones
        $baseDef=array(
            "index"=>$indexName,

            "body"=>array(
                "settings"=>array(
                    "number_of_shards"=>isset($definition["shards"])?$definition["shards"]:ESClient::DEFAULT_INDEX_SHARDS,
                    "number_of_replicas"=>isset($definition["replicas"])?$definition["replicas"]:ESClient::DEFAULT_INDEX_REPLICAS
                ),

                "mappings"=>array("properties"=>$definition)
            )
        );
        \lib\php\ArrayTools::merge($baseDef,$extraDef);
        $result=$client->indices()->create($baseDef);
        if($result["acknowledged"]!=true || $result["shards_acknowledged"]!=true)
        {
            throw new ESClientException(ESClientException::ERR_CREATE_INDEX_ERROR,["indexName"=>$indexName]);
        }
        if($alias!=null)
        {
            $this->createIndexAlias($indexName,$alias);
        }
    }
    function createIndexAlias($indexName,$alias)
    {
        $client=$this->getConnection();
        $params['body'] = array(
            'actions' => array(
                array(
                    'add' => array(
                        'index' => $this->normalizeIndexName($indexName),
                        'alias' => $this->normalizeIndexName($alias)
                    )
                )
            )
        );
        $client->indices()->updateAliases($params);
    }
    function getTypeMapping($def)
    {
        if(!isset($def["type"]))
        {
            return array("type"=>"string",'index' => false);
        }
        $tt=strtolower($def["type"]);
        switch($tt)
        {
            case "string":{

                if(!isset($def["textSearchable"]) || $def["textSearchable"]==false) {
                    $res["type"]="keyword";
                    $res["index"] = true;
                }
                else
                {
                    $res["type"]="text";
                }
            }break;
            case "integer":{
                $res["type"]="integer";
            }break;
            case "datemonth":{
                $res["type"]="string";
                $res["index"]=false;
            }break;
            case "date":{
                $res["type"]="date";
            }break;
            case "money":{
                $res["type"]="float";
            }break;
            default:
                {
                    $res["type"]=strtolower($def["type"]);
                    $res["index"]=false;
                }break;
        }

        if(isset($def["format"]))
            $res["format"]=$def["format"];
        return $res;
    }
    function destroyIndex($index,$ignoreException=true)
    {
        $params = ['index' => $this->normalizeIndexName($index)];
        try {
            $client=$this->getConnection();
            $response = $client->indices()->delete($params);
            if($response["acknowledged"]!==true)
                throw new ESClientException(ESClientException::ERR_DESTROY_INDEX_ERROR,["indexName"=>$index]);
            return $response;
        }catch(\Exception $e){
            $info=json_decode($e->getMessage(),true);
            throw new ESClientException(ESClientException::ERR_DESTROY_INDEX_ERROR,["indexName"=>$index, "description"=>$info["error"]["root_cause"]["reason"]]);
            if($ignoreException==false)
                throw $e;
        }
    }
    function indexExists($name)
    {
        $client=$this->getConnection();
        return $client->indices()->exists(array("index"=>$this->normalizeIndexName($name)));
    }


    function insertBulk($lines,$noErrors=false)
    {
        // Se sustituyen los "*" en el nombre del indice, con la fecha de hoy.
        return $this->recurse_insertBulk($this->index,$lines,$noErrors);
    }
    function recurse_insertBulk($curIndex,$lines,$noErrors=false)
    {
        $client=$this->getConnection();
        $q=array();
        for($k=0;$k<count($lines);$k++)
        {
                $p=array("index"=>array(
                    "_index"=>$curIndex));
                if(isset($lines[$k]["_id"])) {
                    $p["index"]["_id"] = $lines[$k]["_id"];
                    unset($lines[$k]["_id"]);
                        }
                $q[]=$p;
                $q[]=$lines[$k];
        }
        $errored=false;
        try{
            $d=$client->bulk(array("body"=>$q));
        }catch(\Exception $e) {
            $errored = true;
        }
        if(($errored || (isset($d["errors"]) && $d["errors"]==true) ) && $noErrors==true)
            return;


        if(isset($d["errors"]) && $d["errors"]==true) {
            $esError = "";
            foreach ($d["items"] as $key => $value) {
                if(isset($value["index"]["error"]))
                $esError .= (";" . $value["index"]["_index"] . "=>" . $value["index"]["error"]["type"] . "::" . $value["index"]["error"]["reason"]);
            }
            throw new ESClientException(ESClientException::ERR_INSERT_ERROR, ["esError"=>$esError]);
        }
    }
    function select($q)
    {
        if(isset($q["index"]))
            $q["index"]=$this->normalizeIndexName($q["index"]);
        else
            $q["index"]=$this->index;
        return $this->query($q);

    }
    function fetch_agg_tree($q,$aggregationColNames)
    {
        $r=$this->query($q);
        if (isset($r["aggregations"])) {
            $pointer=$r["aggregations"];
            $s=$this->recurseResult($aggregationColNames,$pointer);
            return $s;
        }
    }

    function fetch_agg_plain($q,$aggregationColNames)
    {
        $r=$this->query($q);
        return $this->recurseResultPlain($aggregationColNames,$r);
    }

    function fetch_plain($fields,$query,& $scrollId,$page=1000)
    {
        $parts=explode(",",$fields);
        $cols=array_map(function($item){return trim($item);},$parts);
        $q=[
            "scroll"=>"30s",
            "index" => $this->index,
            "body" =>
                [
                    "_source" => $cols,
                    "size"=>$page,
                    "query" => $query,
                    "timeout" => "30000ms"
                ]
        ];
        $connection=$this->conn->getConnection();
        if($scrollId==null)
            $response = $connection->search($q);

        else
            $response=$connection->scroll(["scroll_id"=>$scrollId,"scroll"=>"30s"]);
        $hits=null;
        if(isset($response['hits']['hits']) && count($response['hits']['hits']) > 0)
        {
            $scrollId=$response["_scroll_id"];
            $hits=$response["hits"]["hits"];
        }
        else
            return null;

        if(isset($hits))
        {
            $nHits=count($hits);
            for($k=0;$k<$nHits;$k++)
            {
                $source=$hits[$k]["_source"];
                $i=array();
                foreach($source as $ki=>$vi)
                    $i[$ki]=$vi;
                $result[]=$i;
            }
        }
        return $result;
    }

    function recurseResult($parts,$pointer)
    {
        $curPart=trim(array_shift($parts));

        if(count($parts)==0)
        {
            if(isset($pointer[$curPart]["buckets"]))
            {
                $b=$pointer[$curPart]["buckets"];
                $result=array();
                for($h=0;$h<count($b);$h++)
                {
                    if(is_float($b[$h]["key"]))
                    {
                        $b[$h]["key"]=round($b[$h]["key"],2);
                    }
                    if($this->aggType=="terms")
                        $result["".$b[$h]["key"]]=$b[$h]["doc_count"];
                    else
                        $result["".$b[$h]["key"]]=$b[$h]["value"];
                }
            }
            else
                $result[$curPart]=$pointer[$curPart]["value"];
            return $result;
        }
        if(isset($pointer[$curPart]))
        {
            $result=array();
            if(isset($pointer[$curPart]["buckets"])) {
                $v = $pointer[$curPart]["buckets"];
                for ($kk = 0; $kk < count($v); $kk++) {
                    $cb = $v[$kk];
                    if (is_float($cb["key"])) {
                        $cb["key"] = round($cb["key"], 2);
                    }
                    $result["" . $cb["key"]] = $this->recurseResult($parts, $cb);

                }
            }
            return $result;
        }

        return [];
    }
    function recurseResultPlain($colNames,& $r)
    {
        $f=null;
        $f=function($pointer,$colNames,$colIndex,$nCols) use (& $f) {

            $result=array();
            if ($colIndex == $nCols - 1) {
                foreach ($pointer as $k => $v) {
                    $result[]=array($colNames[$colIndex] => $k,"agg"=>$v);
                }

            }
            else
            {
                $colName=$colNames[$colIndex];
                foreach($pointer as $k=>$v){
                    $newResults=$f($v,$colNames,$colIndex+1,$nCols);
                    foreach($newResults as $k1=>& $v1)
                        $v1[$colName]=$k;
                    $result=array_merge($result,$newResults);
                }
            }
            return $result;
        };
        return $f($r,$colNames,0,count($colNames));
    }

    function getCount($query=null,$index=null)
    {
        $client=$this->getConnection();
        $params=["index"=>$index==null?$this->index:$index];
        if($query!=null) {
            $params["body"]=["track_total_hits"=>true];
            $params["body"]["query"] = $query;
        }
        $result=$client->count(
            $params
        );
        return $result["count"];
    }
    function delete($query,$index=null)
    {
        $client=$this->getConnection();
        $params=["index"=>$index==null?$this->index:$this->normalizeIndexName($index)];
        if($query!=null)
            $params["body"]=["query"=>$query];
        $client->deleteByQuery($params);
    }


}
