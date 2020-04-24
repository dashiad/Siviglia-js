<?php
namespace lib\storage\Es;
use Elasticsearch\Serializers\EverythingToJSONSerializer;

class QueryBuilder extends \lib\datasource\BaseQueryBuilder
{

    var $findRows;
    var $data;
    var $sqlData;



    function makeRegExp($val)
    {
        return "/@@" . $val . "@@/";
    }


    function getColNames()
    {
        if(!isset($this->definition["GROUPBY"]))
            return null;

        $parts=explode("=>",$this->definition["GROUPBY"]);
        for($k=0;$k<count($parts);$k++)
        {
            $parts[$k]=trim($parts[$k]);
            $parts[$k]=preg_replace("/\\([^)]+\\)/","",$parts[$k]);
            $parts[$k]=preg_replace("/\\[[^)]+\\]/","",$parts[$k]);
        }
        return $parts;
    }
    function build($onlyConditions = false)
    {
        global $registry;

        $curQuery = $this->definition;
        if ($onlyConditions == false) {
            if(isset($curQuery["BASE"])) {
                if (is_array($curQuery["BASE"])) {
                    $selectStr = '{
                    "index":"' . $curQuery["INDEX"] . '",';
                    if(count($curQuery["BASE"])>0)
                        $selectStr.='"_source":["' . implode('","', $curQuery["BASE"]) . '"],';
                    $selectStr.='    
                    "timeout":"30000ms",
                    [##PAGING##]
                    "body":{                    
                            [##CONDITIONS##]                            
                        }
                    }';
                } else
                    $selectStr = trim($curQuery["BASE"]);
            }
            else
            {
                $selectStr = '{
                    "index":"' . $curQuery["INDEX"] . '",                    
                    "timeout":"30000ms",
                    [##PAGING##]
                    "body":{                    
                            [##CONDITIONS##]                            
                        }
                    }';
            }
        } else
            $selectStr = "";
        $queryText = $selectStr;
        //Construimos las condiciones
        $conds = $this->buildConditions($queryText);
        if($conds!==null) {
            $replaced = $this->replaceParams($conds);
            // FIXME : No hay forma menos sucia de limpiar el json??
            $replaced = preg_replace("/,,+/", ",", $replaced);
            $replaced = preg_replace("/,([}\]])/", "$1", $replaced);
            $replaced = preg_replace("/([{\[]),/", "$1", $replaced);


            $conds = json_decode($replaced, true);
            if($conds==null)
            {
                // TODO : Poner aqui una excepcion!!
                throw new \Exception();
            }
            $conds = $this->reduceJson($conds);
        }

        $bodyParts=[];
        $mustParts=[];
        if(isset($conds["must"]))
            $mustParts[]='"must":'.json_encode($conds["must"]);
        if(isset($conds["must_not"]))
            $mustParts[]='"must_not":'.json_encode($conds["must_not"]);
        if(count($mustParts)>0) {
            $must = '"query":{"bool":{' . implode(",", $mustParts) . '}}';
            $bodyParts[]=$must;
        }

        if (isset($curQuery["GROUPBY"]))
        {
            // Group by se espera que sea una expresion de este tipo:
            // A => B => C => D
            // Esta expresion, a su vez, puede tener modificadores que van entre parentesis o corchetes, DELANTE
            // del campo, para evitar problemas con el uso de funciones en Mysql
            $aggs='"aggs":'.json_encode($this->buildAggregation($curQuery["GROUPBY"]));
            $bodyParts[]=$aggs;
        }

        $orderBy = isset($curQuery["DEFAULT_ORDER"]) ? $curQuery["DEFAULT_ORDER"] : (isset($curQuery["ORDERBY"])?$curQuery["ORDERBY"]:null);
        $orderDirection = isset($curQuery["DEFAULT_ORDER_DIRECTION"]) ? $curQuery["DEFAULT_ORDER_DIRECTION"] : (isset($curQuery["ORDERTYPE"])?$curQuery["ORDERTYPE"]:"ASC");
        if($orderBy!==null)
        {
            if(!is_array($orderBy))
            {
                $k=$orderBy;
                $orderBy=[$k=>$orderDirection];
            }

                $sort=',"sort":[';
                $n=0;
                foreach($orderBy as $key=>$value)
                {
                    $sort.=($n?",":"").'{"$key":"'.strtolower($value).'"}';
                    $n++;
                }
                $bodyParts[]=$sort;
        }
        $queryText=str_replace("[##CONDITIONS##]",implode(",",$bodyParts),$queryText);

        $paging="";
        /**
         * NOTA :: EN ES, NO ES POSIBLE HACER SCROLL MAS ALLA DE index.max_result_window, QUE POR DEFECTO ES 10000.
         * PARA HACER OTROS SCROLLS, ES MEJOR USAR LA API DE SCROLL.
         */
        if (isset($curQuery["PAGESIZE"]))
        {
            $paging= '"size":'.$curQuery["PAGESIZE"];
            if (isset($curQuery["STARTINGROW"]))
                $paging .= ',"from":'.$curQuery["STARTINGROW"];
            $paging.=",";

        } else {
            if (isset($curQuery["LIMIT"]))
               $paging .= '"size":'.$curQuery["LIMIT"].",";
        }
        $queryText=str_replace("[##PAGING##]",$paging,$queryText);


        //Modificamos la query text para incorporar las partes interiores
        $queryText = $this->buildInnerParts($queryText);

        // Se hace el reemplazo final, obteniendo los campos serializados.
        preg_match_all('/\"{\%([^%]+)\%}\"/', $queryText, $matches);
        if (count($matches[1]) > 0 && !$this->data) {
            // TODO: throw exception
            return false;
        }
        $keys=array();
        $values=array();
        foreach ($matches[1] as $key => $value) {
            $curField=$this->data->__getField($value);

            $fdef=$curField->getDefinition();

            if(isset($fdef["PARAMTYPE"]) && $fdef["PARAMTYPE"]=="DYNAMIC")
            {
                $val=$curField->getValue().'%';
                if(isset($fdef["DYNAMICTYPE"]) && $fdef["DYNAMICTYPE"]=="BOTH")
                {
                    $val='%'.$val;
                }
                $curField->setValue($val);
            }
            $serializedVal = str_replace("'",'"',$curField->serialize($this->serializer));
            $keys[] = $matches[0][$key];
            $values[] = $serializedVal;
        }
        $qText = str_replace($keys, $values, $queryText);
        //echo $qText;
        // Se reemplazan las constantes
        preg_match_all("/\|\%([^%]*)\%\|/", $qText, $matches);
        if (isset($matches[1])) {
            $constants = get_defined_constants(true);
            for ($k = 0; $k < count($matches[1]); $k++) {
                //Vemos si es un valor metido en el registro
                if (strstr($matches[1][$k], '/registry/')) {
                    preg_match('#/registry/(.*)#', $matches[1][$k], $moreMatches);
                    $tr = explode('?', $moreMatches[1]);
                    try {
                        $value = \Registry::retrieve($tr[0]);
                    }
                    catch(\Exception $e) {
                        $value = $tr[1];
                    }
                    $qText = str_replace("|%" . $matches[1][$k] . "%|", $value, $qText);
                }
                else {
                    if($matches[1][$k]=="currentUser") {
                    // TODO : Tirar excepcion cuando el usuario no esta logeado.
                    global $oCurrentUser;
                    if($oCurrentUser->isLogged())
                        $qText=str_replace("|%currentUser%|",$oCurrentUser->getId(),$qText);
                    else
                        $qText=str_replace("|%currentUser%|","false",$qText);
                    continue;
                }
                $c = $matches[1][$k];
                $qText = str_replace("|%" . $c . "%|", $constants["user"][$c], $qText);
            }
        }
        }
        $current=json_decode($qText,true);
        // Si hay agregaciones, no queremos hits.
        if(isset($current["body"]["aggs"])) {
            $current["size"]=0;
            $current["body"]["size"] = 0;
        }
        $current["body"]["track_total_hits"]=true;
        return $current;
    }

    /**
     * @param $base
     * @return string|string[]|null
     * Esto parece hacer lo siguiente:
     * Si en la base de la query, existe un texto del tipo {%aa:mm%}, y la condition llamada "aa" se va a activar,
     * se sutituye el valor con "mm"
     */

    function buildInnerParts($base)
    {
        $curQuery = $this->definition;
        if(!isset($curQuery["CONDITIONS"]))
            return $base;
        $conditions = $curQuery["CONDITIONS"];
        if (!$conditions) return $base;

        foreach ($conditions as $conditionName => $curCondition) {
            if (isset($curCondition["TRIGGER_VAR"])) {
                $tVar = $curCondition["TRIGGER_VAR"];
                try {
                    $curField = $this->data->__getField($tVar);
                } catch (\Exception $e) {
                    continue;
                }

                if ($curField->getType()->hasOwnValue()) {
                    //Tenemos que buscar si existe esa parte y substituirla
                    $val = $curField->getType()->getValue();
                    // Val puede ser un array.
                    // TODO : Gestionar el array.
                    $inEnable=false;
                    if(isset($curCondition["ENABLE_IF"]))
                        $inEnable = in_array($val, (array)$curCondition["ENABLE_IF"]);
                    $hasDisable = isset($curCondition["DISABLE_IF"]);
                    $hasEnable = isset($curCondition["ENABLE_IF"]);
                    if ($hasDisable && $this->checkDisableValue($val, (array)$curCondition["DISABLE_IF"]))
                        $addPart = false;
                    else {
                        if ($hasEnable)
                            $addPart = $inEnable;
                        else
                            $addPart = true;
                    }

                    if ($addPart) {
                        $replacement = '\\1';
                    }
                    else {
                        $replacement = '';
                    }
                }
                else {
                    $replacement = '';
                }

                $pattern = '/{%'.$tVar.':(.*)%}/';
                $base = preg_replace($pattern, $replacement, $base);
            }
        }
        return $base;
    }

    function removeUnusedParts($base)
    {
        return preg_replace('/{%.*%}/', '', $base);
    }

    function buildConditions($base)
    {
        $curQuery = $this->definition;
        $conditions = io($curQuery,"CONDITIONS",null);

        if (!$conditions)
            return null;
        $notExisting = array();
        $nSubConditions = 0;
        //$this->buildParams();
        $must=[
            "terms"=>[],
            "exists"=>[],
            "wildcard"=>[],
            "regexp"=>[],
            "range"=>[]
        ];
        $mustNot=[
            "terms"=>[],
            "exists"=>[],
            "wildcard"=>[],
            "regexp"=>[]
        ];


        foreach ($conditions as $conditionName => $curCondition) {
                if (is_array($curCondition["FILTER"])) {
                    $field=$curCondition["FILTER"]["F"];
                    $value=$curCondition["FILTER"]["V"];
                    if($value[0]==="[")
                        $value[0]="{";
                    if($value[strlen($value)-1]==="]")
                        $value[strlen($value)-1]="}";
                    $trigger=(isset($curCondition["TRIGGER_VAR"])?$curCondition["TRIGGER_VAR"]:null);
                    switch($curCondition["FILTER"]["OP"])
                    {
                        case "=":{
                            $must["terms"][$field]=["trigger"=>$trigger,"value"=>$value];

                        }break;
                        case "IN":{
                            $must["terms"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case "!=":{
                            $mustNot["terms"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case ">":
                        case ">=":
                        case "<=":
                        case "<":{
                            $ops=[">"=>"gt",">="=>"gte","<"=>"lt","<="=>"lte"];
                            if(!isset($ops[$curCondition["FILTER"]["OP"]]))
                            {
                                throw new ESSerializerException(ESSerializerException::ERR_UNKNOWN_OPERATOR);
                            }
                            if(!isset($must["range"][$field]))
                                $must["range"][$field]=[];
                            $must["range"][$field][$ops[$curCondition["FILTER"]["OP"]]]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case "EXISTS":{
                            $must["exists"][$field]=["trigger"=>$trigger,"value"=>1];
                        }break;
                        case "NOT EXISTS":{
                            $mustNot["exists"][$field]=["trigger"=>$trigger,"value"=>1];
                        }break;
                        case "LIKE":{
                            $must["wildcard"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case "NOT LIKE":{
                            $mustNot["wildcard"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case "MATCHES":{
                            $must["regexp"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }break;
                        case "NOT MATCHES":{
                            $mustNot["regexp"][$field]=["trigger"=>$trigger,"value"=>$value];
                        }


                    }
                } else {
                    throw new ESSerializerException(ESSerializerException::ERR_UNSUPPORTED);
                }
            }
        // Reconstruir los must y must-not
        $baseVars=["must"=>$must,"must_not"=>$mustNot];
        $conditions=[];
        $options=[];
        foreach($baseVars as $bVk=>$bVv)
        {
            $cV=$bVv;
            $subKeys=["terms","exists","wildcard","regexp","range"];
            $fragments=[];
            for($j=0;$j<count($subKeys);$j++)
            {
                $cK=$subKeys[$j];
                $curText="";
                if(isset($cV[$cK])) {
                    $n = 0;
                    $subFragments=[];
                    foreach ($cV[$cK] as $kk => $vvs) {
                        switch ($cK) {
                            case "terms":{
                                $trigger=$vvs["trigger"];
                                if($trigger) {
                                    $curText = "[%$trigger:";
                                    $curText .= '{"term":{';
                                    $vv = $vvs["value"];
                                    $curText .= '"' . $kk . '":' . $vv . '}},';
                                    $curText .= '%]';
                                    $subFragments["terms"][] = $curText;
                                }
                                else
                                    $subFragments["terms"][]='{"term":{"'.$kk.'":'.json_encode($vvs["value"]).'}}';
                            }break;
                            case "regexp":
                            case "wildcard":
                                {

                                    $vv=$vvs["value"];
                                    $trigger=$vvs["trigger"];
                                    if($trigger) {
                                        $curText = '[%' . $trigger . ':{"' . $cK . '":{';
                                        $curText .= '"' . $kk . '":"' . $vv;
                                        $curText .= "}}%]";
                                        $subFragments[$cK][] = $curText;
                                    }
                                    else
                                        $subFragments[$cK][]='{"'.$cK.'":{"'.$kk."':".json_encode($vv)."}}";
                                }
                                break;
                            case "exists":
                                {
                                    if ($n == 0)
                                        $curText = '"' . $cK . '":{';
                                    if ($n > 0)
                                        $curText = ",";
                                    $trigger=$vvs["trigger"];
                                    if($trigger) {
                                        $curText = '[%' . $trigger . ':{"' . $cK . '":{';
                                        $curText .= '"field":"' . $kk . '"';
                                        $curText .= "}}%]";
                                        $subFragments[$cK][] = $curText;
                                    }
                                    else
                                        $subFragments[$cK][]='{"'.$cK.'":{"field":"'.$kk.'"}}';
                                }
                                break;
                            case "range":
                                {
                                    // obtenemos el formato de la variable trigger,
                                    $curText = '{"'.$kk.'":{';
                                    $parts = [];

                                    $vv=$vvs;
                                    foreach ($vv as $op => $val) {
                                        $trigger=$val["trigger"];
                                        $txt="";
                                        if($trigger) {
                                            $txt .= "[%$trigger:";
                                            $txt .= '"' . $op . '":' . $val["value"];
                                            $txt .= ',%]';
                                            $parts[] = $txt;
                                        }
                                        else
                                        {
                                            $parts[]='"'.$op.'":'.json_encode($val["value"]).",";
                                        }

                                    }
                                    $curText .= implode(",", $parts);
                                    $curText.="}}";
                                    $subFragments[$cK][]=$curText;
                                }
                                break;
                        }
                        $n++;
                    }
                    $partFragments=[];
                    $nn=0;
                    foreach($subFragments as $kf=>$vf)
                    {
                        foreach($vf as $vf1=>$vf2) {
                            $nn++;
                            if($kf==="terms")
                                $partFragments[]=$vf2;
                            else
                                $partFragments[] = '{"' . $kf . '":' . $vf2 . '}';
                        }
                    }
                    if ($nn !== 0) {

                        $fragments[] = implode(",",$partFragments);
                    }
                }
            }
            if(count($fragments)>0)
            {

                    $conditions[]='"'.$bVk.'":['.implode(",",$fragments)."]";

            }
        }

        return "{".implode(",",$conditions)."}";
    }

    function checkDisableValue($val, $conditions)
    {
        if (in_array($val, $conditions, true)) {
            return true;
        }

        return false;
    }

    function buildAggregation($aggs)
    {
        $parts=explode("=>",$aggs);
        $aggSpec=array();
        $curSpec=& $aggSpec;

        for($k=0;$k<count($parts);$k++)
        {
            $f=trim($parts[$k]);
            $matches=array();
            // Si es un histograma, el "step" va entre parentesis: ej: actualfloor(0.1)
            preg_match("/^\\((.*)\\)/",$f,$matches);

                $curSpec["aggs"]=[];
                if(is_array($matches) && count($matches)<1) {
                    $agg="terms";
                    // Si la agregacion no es de terminos, va entre corchetes, ej: [sum]actualfloor
                    preg_match("/^\\[(.*)\\]/",$f,$matches);
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

            return $aggSpec["aggs"];
        }
        function getSerializerType()
        {
            // TODO: Implement getSerializerType() method.
            return \lib\storage\ES\ESSerializer::ES_SERIALIZER_TYPE;
        }
    function getDynamicParamValue($paramValue, $paramType)
    {

        $val=$paramValue.'*';
        if($paramType=="BOTH")
        {
            $val='*'.$val;
        }
        return $val;
    }
    function reduceJson($v)
    {
        if(is_array($v))
        {
            if (array() === $v) return null;
            $associative=array_keys($v) !== range(0, count($v) - 1);
            if($associative)
            {
                $res=[];
                $n=0;
                foreach($v as $k1=>$v1)
                {
                    $d=$this->reduceJson($v1);
                    if($d!==null) {
                        $n++;
                        $res[$k1] = $d;
                    }
                }
                return $n==0?null:$res;
            }
            else
            {
                $res=[];
                for($k=0;$k<count($v);$k++)
                {
                    $d=$this->reduceJson($v[$k]);
                    if($d!==null)
                        $res[]=$d;
                }
                return (count($res)==0)?null:$res;
            }
        }
        return $v;
    }

}
