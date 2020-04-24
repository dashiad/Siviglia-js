<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 15/04/2019
 * Time: 22:13
 */

namespace lib\ES;
include_once(__DIR__ . "./ESClient.php");


class ESBaseQuery
{
    var $aggType;
    function __construct($hosts)
    {
        $this->conn=new ESClient($hosts);
        $this->aggType="terms";
    }
// La especificacion va a ser:
    // Agregaciones : campo => campo => campo ....
    // Filtros
    function buildAggregation($aggs,$query,$doc="events",$index="gpt*")
    {
        $parts=explode("=>",$aggs);
        $aggSpec=array();
        $curSpec=& $aggSpec;
        $parsed=array();
        for($k=0;$k<count($parts);$k++)
        {
            $f=trim($parts[$k]);
            $matches=array();
            // Si es un histograma, el "step" va entre parentesis: ej: actualfloor(0.1)
            preg_match("/\\((.*)\\)/",$f,$matches);

            $curSpec["aggs"]=[];
            if(is_array($matches) && count($matches)<1) {
                $agg="terms";
                // Si la agregacion no es de terminos, va entre corchetes, ej: [sum]actualfloor
                preg_match("/\\[(.*)\\]/",$f,$matches);
                if(is_array($matches) && count($matches)>0)
                {
                    $f=str_replace($matches[0],"",$f);
                    $agg=$matches[1];
                }
                $curSpec["aggs"][$f] = [
                    $agg => [
                        "field" => $f
                    ]
                ];
                if($agg=="terms")
                {
                    $this->aggType=$agg;
                    $curSpec["aggs"][$f]["terms"]["size"] = 1000;
                }
            }
            else
            {
                $f=str_replace($matches[0],"",$f);
                $curSpec["aggs"][$f]=[
                    "histogram"=>[
                        "field"=>$f,"interval"=>$matches[1]
                    ]
                ];
            }
            $curSpec =& $curSpec["aggs"][$f];
        }

        return [
            "index" => $index,
            "body" =>
                [
                    "aggs" => $aggSpec["aggs"],
                    "size" => 0,
                    "query" => $query,
                    "timeout" => "30000ms"
                ]
        ];
    }
    function getColNames($aggs)
    {
        $parts=explode("=>",$aggs);
        for($k=0;$k<count($parts);$k++)
        {
            $parts[$k]=trim($parts[$k]);
            $parts[$k]=preg_replace("/\\([^)]+\\)/","",$parts[$k]);
            $parts[$k]=preg_replace("/\\[[^)]+\\]/","",$parts[$k]);
        }
        return $parts;
    }

    /**
     * @param $aggs : El formato es: "DATE => fetched_on => DEVICE_CATEGORY_NAME => AD_EXCHANGE_PRICING_RULE_NAME => [sum]AD_EXCHANGE_AD_ECPM". Para histogramas, usar (.01)
     * @param $query : Base de la query de ES, con filtros, etc
     * @param string $doc : Tipo de documento de ES
     * @param string $index : Indice sobre el que ejecutar la query
     * @return mixed : arbol de resultados.
     */
    function fetch_agg($aggs,$query,$doc="events",$index="gpt*")
    {
        $q=$this->buildAggregation($aggs,$query,$doc,$index);
        $stringQ=json_encode($q);
        return $this->conn->query($q);
    }
    function fetch_agg_tree($aggs,$query,$doc="events",$index="gpt*")
    {
        $q=$this->buildAggregation($aggs,$query,$doc,$index);

        $r=$this->conn->query($q);

        if (isset($r["aggregations"])) {

            $parts=$this->getColNames($aggs);
            $pointer=$r["aggregations"];
            $s=$this->recurseResult($parts,$pointer);
            return $s;
        }
    }

    function fetch_agg_plain($aggs,$query,$doc,$index)
    {
        $r=$this->fetch_agg_tree($aggs,$query,$doc,$index);
        $colNames=$this->getColNames($aggs);
        $result=array();
        return $this->recurseResultPlain($colNames,$r);

    }

    function fetch_plain($fields,$query,$doc,$index,& $scrollId,$page=1000)
    {
        $parts=explode(",",$fields);
        $cols=array_map(function($item){return trim($item);},$parts);
        $q=[
            "scroll"=>"30s",
            "type" => $doc,
            "index" => $index,
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
}
