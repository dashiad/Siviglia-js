<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 02/09/2016
 * Time: 11:10
 */

namespace lib\storage\ElasticSearch;
include_once(PROJECTPATH."/vendor/autoload.php");

class ElasticSearch
{
    static $connection=null;
    var $hosts;
    function __construct($hosts)
    {
        $this->hosts=$hosts;
    }
    function getConnection()
    {
        if(ElasticSearch::$connection!=null)
            return ElasticSearch::$connection;
	echo "Connecting to ES\n";
        ElasticSearch::$connection=$client = \Elasticsearch\ClientBuilder::create()->setHosts($this->hosts)->build();
	echo "Connected\n";
        return ElasticSearch::$connection;
    }
    function updateIndexMapping($indexName,$typeName,$queryName,$queryFields,$dataDefinition,$alias=null)
    {
        $indexName=strtolower($indexName);
        $client=$this->getConnection();
        $fields=array();
        $definitions=array();
        $fieldDefinition=$dataDefinition["tables"][$queryName]["fields"];
        if(!$this->indexExists($indexName))
        {
	    echo "Indice no existe...Recreando..$indexName\n";
            $result=$client->indices()->create(
                array(
                    "index"=>$indexName,
                    "body"=>array(
                        "settings"=>array(
                            "number_of_shards"=>ELASTICSEARCH_SHARDS,
                            "number_of_replicas"=>ELASTICSEARCH_REPLICAS
                        )
                    )
                )
            );
	    var_dump($result);
        }
	else
		echo "INDICE EXISTE:$indexName\n";
	try{
	echo "CARGANDO MAPPING $indexName\n";
        $currentMapping=$client->indices()->getMapping(['index' => $indexName]);
	var_dump($currentMapping);
	echo "MAPPING CARGADO\n";
	}catch(Exception $e)
	{
	echo "EXCEPCION EN CARGA DE MAPPING\n";
	$currentMapping=array();
	}
	echo "CONTINUO\n";
        $existing=$currentMapping[$indexName]["mappings"];
        $newMaps=array();
        $nMaps=0;
        foreach($queryFields  as $value)
        {
            $v1=$fieldDefinition[$value];
            if(isset($existing["_default_"][$value]))
                continue;
            $nMaps++;

            $newMaps["properties"][$value]=$this->getTypeMapping($v1);
            echo "Typemap: $value => ".$newMaps["properties"][$value]["type"]."\n";
        }
        // Los campos autogenerados tambien deben ir en la definicion de tipos.
        foreach($fieldDefinition as $k=>$v)
        {
            if(!isset($v["generated"]) || $v["generated"]==false)
                continue;
            if(isset($existing[$typeName][$k]))
                continue;
            $nMaps++;
            $newMaps["properties"][$k]=$this->getTypeMapping($v);
        }
        if($nMaps==0)
	{
	    die("SALIENDO POR nMAPS==0");
            return;
	}
	if(!$this->indexExists($indexName))
	   echo "OJO! Ahora $indexName NO EXISTE!\n";
	else
	   echo "$indexName SIGUE EXISTIENDO\n";
	echo "CREANDO MAPPING $indexName\n";
	try{
        $response=$client->indices()->putMapping(
            array(
                'index' => $indexName,
                'type'=>$typeName,
                'body' => [
                    "$typeName"=>$newMaps
                ],
		"client"=>array("verbose"=>true)
            )
        );
	}catch(\Exception $e)
	{
		echo "ERROR CREANDO MAPPING!\n";
		var_dump($e);
	}

	print_r($response);
	if($response["status"]!=200)
		die();
	echo "MAPPING CREADO\n";
    }
    function createIndexFromDataDefinition($indexName,$dataDefinition,$alias=null)
    {
        $indexName=strtolower($indexName);
        $definition=array();
        foreach($dataDefinition["tables"] as $key=>$value)
        {
            $tableName=$key;
            $c=$value;
            if(isset($c["fields"]))
            {
                foreach($c["fields"] as $k1=>$v1)
                {
                    $definition["_default_"]["properties"][$k1]=$this->getTypeMapping($v1);
                }
            }
        }
        $this->createIndex($indexName,$definition,$alias);

    }

    function createIndex($indexName,$definition,$alias=null)
    {
        $client=$this->getConnection();
        // TODO : Controlar y lanzar excepciones
        $result=$client->indices()->create(
            array(
                "index"=>$indexName,
                "body"=>array(
                    "settings"=>array(
                        "number_of_shards"=>ELASTICSEARCH_SHARDS,
                        "number_of_replicas"=>ELASTICSEARCH_REPLICAS
                    ),
                    "mappings"=>$definition
                )
            )
        );
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
                        'index' => $indexName,
                        'alias' => $alias
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
        $params = ['index' => strtolower($index)];
        try {
            $client=$this->getConnection();
            $response = $client->indices()->delete($params);
        }catch(\Exception $e){
            if($ignoreException==false)
                throw $e;
        }
    }
    function indexExists($name)
    {
        $client=$this->getConnection();
        return $client->indices()->exists(array("index"=>strtolower($name)));
    }
    function typeExists($index,$name)
    {
        $client=$this->getConnection();
        return $client->indices()->existsType(array("index"=>strtolower($index),"type"=>$name));
    }

    function insertBulk($index,$type,$lines)
    {
	return $this->recurse_insertBulk($index,$type,$lines);
    }
    function recurse_insertBulk($index,$type,$lines)
    {
        $index=strtolower($index);

        $client=$this->getConnection();
        $params=array(
            "index"=>array(
                "_index"=>$index,
                "_type"=>$type
            )
        );
        $q=array();
	$half=array();
        for($k=0;$k<count($lines);$k++)
        {
            $q[]=$params;
            $q[]=$lines[$k];
	    $half[$k%2][]=$lines[$k];
        }
	echo "Inserting Bulk\n";
	echo "Cliente conectando....\n";
	try{
        $d=$client->bulk(array("body"=>$q));
	}catch(\Exception $e)
	{
	   if(count($lines)==1)
	   {
	     echo "*******************ENCONTRADA LINEA ERRONEA*************";
	     var_dump($e);
	     die();
	   }
	   else
	   {
	       $this->recurse_insertBulk($index,$type,$half[0]);
	       $this->recurse_insertBulk($index,$type,$half[1]);
	   }
	}
	echo "Fin de conexion\n";
	return $d;
    }
    function loadIndexType($index,$type)
    {
        $client=$this->getConnection();
        $params=array(

            "size" => 10000,
            "query" => [
                "match_all" =>  (object)[]
            ]
        );
        $docs = $this->rawGet("/".$index."/".$type."/_search?scroll=30s",$params);

// Now we loop until the scroll "cursors" are exhausted
        $results=array();
        while (\true) {
            $scroll_id = $docs['_scroll_id'];   // The response will contain no results, just a _scroll_id
            // Check to see if we got any search hits from the scroll
            $r=$docs['hits']['hits'];
            $n=count($r);
            if($n>0){
                for($k=0;$k<$n;$k++)
                    $results[]=$r[$k]["_source"];
            } else {
                // No results, scroll cursor is empty.  You've exported all the data
                break;
            }
            // Execute a Scroll request
            $docs = $client->scroll([
                    "scroll_id" => $scroll_id,  //...using our previously obtained _scroll_id
                    "scroll" => "30s"           // and the same timeout window
                ]
            );
        }

        return array("status"=>"OK","results"=>$results);
    }
    function rawGet($url,$params)
    {
        $hosts=ELASTICSEARCH_HOST;
        if(is_array($hosts))
            $hosts=$hosts[0];
        $url="http://".$hosts.":9200".$url;

        if(is_array($params))
            $params=json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
        ob_start();
        curl_exec ($ch);
        curl_close ($ch);
        $data = ob_get_contents();
        ob_end_clean();
        return json_decode($data,true);
    }

}
