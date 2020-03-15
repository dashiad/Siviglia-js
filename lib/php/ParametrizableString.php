<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 28/08/15
 * Time: 13:05
 */

namespace lib\php;
use lib\model\BaseException;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

class ParametrizableStringException extends BaseException
{
    const ERR_MISSING_REQUIRED_PARAM=1;
    const ERR_MISSING_REQUIRED_VALUE=2;
    const TXT_MISSING_REQUIRED_PARAM="Missing parameter [%param%]";
    const TXT_MISSING_REQUIRED_VALUE="Missing value [%param%]";
}


class ParametrizableString
{
    static $usedParams=array();
    const BASEREGEXP='/\[\%(?:(?:(?<simple>[^: ,%]*)\%\])|(?:(?<complex1>[^: ,]*)|(?<complex2>[^:]*)):(?<body>.*?(?=\%\]))\%\])/';
    const BODYREGEXP='/{\%(?:(?<simple>[^%:]*)|(?:(?<complex>[^:]*):(?<predicates>.*?(?=\%}))))\%}/';
    const PARAMREGEXP='/(?<func>[^|$ ]+)(?:\||$|(?: (?<params>[^|$]+)))/';
    const SUBPARAMREGEXP="/('[^']*')|([^ ]+)/";
    // Si en source tenemos una cadena del tipo:
    // "[%param: a={%param%}%] [%!param2: b=0%]"
    // Y en $params tenemos array("param"=>2)
    // La salida de esta funcion, debe ser: a=2 b=0
    // Si ponemos en params array("param"=>2,"param2"=>1), la salida es sólo a=2
    // El modificador "!" delante del nombre del parametro, indica "haz esto si no está definido"
    static function getParametrizedString($source,$params,$unusedReplacement="")
    {
        ParametrizableString::$usedParams=array();
        if(!$params)
            $params=array();
        $stack=null;
        if(!is_object($params) || !is_a($params,'\lib\model\ContextStack'))
        {
            $stack=new \lib\model\ContextStack();
            $ctx=new \lib\model\BaseObjectContext($params,"/",$stack);
        }
        else
            $stack=$params;
        /*$start='\[\%';
        $end='\%\]';
        $simpleTag='(?<simple>[^: ,%]*)';
        $complexTag1='(?<complex1>[^: ,]*)';
        $complexTag2='(?<complex2>[^:]*)';
        $joinedComplex="(?:".$complexTag1."|".$complexTag2.")";
        $simpleTagRegexp=$start.$simpleTag.$end;
        $body=':(?<body>.*(?=\%\]))';
        $complexTagRegexp=$joinedComplex.$body.$end;

        $fullRegex="/".$start."(?:(?:".$simpleTag.$end.")|".$complexTagRegexp.")/";
        echo $fullRegex;*/
        $f=function($matches) use ($stack){
            return ParametrizableString::parseTopMatch($matches,$stack);
        };
        return preg_replace_callback(ParametrizableString::BASEREGEXP,$f,$source);
    }
    static function parseTopMatch($match,$stack)
    {

        $t=$match["simple"];
        if($t)
        {
            try{
                return ParametrizableString::getValue($t,$stack);
            }
            catch(\Exception $e)
            {
                throw new ParametrizableStringException(ParametrizableStringException::ERR_MISSING_REQUIRED_PARAM,array("param"=>$t));
            }
        }
        $t=$match["complex1"];
        $t1=$match["complex2"];
        $mustInclude=false;
        $body='';

        if($t)
        {
            $paramName=$t;
            $negated=(substr($t,0,1)=="!");
            $exists=false;
            if($negated)
                $paramName=substr($t,1);
            try{
                ParametrizableString::getValue($paramName,$stack);
                $exists=true;

            }catch(\Exception $e){

            }
            $mustInclude=((!$negated && $exists) || ($negated && !$exists));
        }
        else
        {
            $mustInclude=ParametrizableString::parseComplexTag($t1,$stack);
        }

        if($mustInclude)
        {
            $f2=function($m2) use ($stack){
                return ParametrizableString::parseBody($m2,$stack);
            };
            $body=preg_replace_callback(ParametrizableString::BODYREGEXP,$f2,$match["body"]);
        }
        return $body;
    }
    static function getValue($paramName,$stack)
    {
        if(!$stack->hasPrefix($paramName[0]))
        {
            $paramName="/".$paramName;
        }
        $controller=new \lib\model\PathResolver($stack,$paramName);
        return $controller->getPath();
    }
    static function parseBody($match,$stack)
    {
        $v=$match["simple"];
        if($v)
        {
            try{
                return ParametrizableString::getValue($v,$stack);
            }catch(\Exception $e)
            {
                throw new ParametrizableStringException(ParametrizableStringException::ERR_MISSING_REQUIRED_VALUE,array("param"=>$v));
            }
        }

        $tag=$match["complex"];
        $cVal=null;
        try {
            $cVal = ParametrizableString::getValue($tag,$stack);
        }catch(\Exception $e)
        {

        }
        if($cVal)
            ParametrizableString::$usedParams[]=$tag;
        preg_match_all(ParametrizableString::PARAMREGEXP,$match["predicates"],$matches);
        $nMatches=count($matches[0]);
        for($k=0;$k<$nMatches;$k++)
        {
            $func=$matches["func"][$k];
            $args=$matches["params"][$k];
            if($func=="default")
            {
                if($cVal===null)
                    $cVal=trim($args,"'");
                continue;
            }
            if($args=="")
            {
                if($cVal===null)
                {
                    throw new ParametrizableStringException(ParametrizableStringException::ERR_MISSING_REQUIRED_VALUE,array("param"=>$v));
                }
                $cVal=$func($cVal);
                continue;
            }
            // Hay varios parametros.Hacemos otra regex para obtenerlos.
            preg_match_all(ParametrizableString::SUBPARAMREGEXP,$args,$matches2);
            $pars=array();
            $nPars=count($matches2[0]);
            for($j=0;$j<$nPars;$j++)
            {
                $arg=$matches2[1][$j]?trim($matches2[1][$j],"'"):$matches2[2][$j];
                if($arg=="@@")
                    $pars[]=$cVal;
                else
                    $pars[]=$arg;
            }
            $cVal=call_user_func_array($func,$pars);
        }
        return $cVal;
    }
    static function parseComplexTag($format,$stack)
    {
        $parts=explode(",",$format);
        $nParts=count($parts);
        for($k=0;$k<$nParts;$k++)
        {
            $cf=$parts[$k];
            $sParts=explode(" ",$cf);
            $negated=$sParts[0][0]=="!";
            if($negated)
                $tag=substr($sParts[0],1);
            else
                $tag=$sParts[0];

            if(count($sParts)==1)
            {
                // Solo esta el tag.En caso de que este negado, y exista, devolvemos false.
                if($negated)
                {
                    if(isset($params[$tag]))
                    {
                        return false;
                    }
                    // Si no esta el tag,y esta negado, continuamos, no hay que procesar mas nada
                    continue;
                }
            }
            // Si no esta el tag actual, lanzamos excepcion.
            $curValue="";
            try{
                $curValue=ParametrizableString::getValue($tag,$stack);
            }
            catch(\Exception $e) {
                throw new ParametrizableStringException(ParametrizableStringException::ERR_MISSING_REQUIRED_PARAM, array("param" => $tag));
            }


            $result=false;
            switch($sParts[1])
            {
                case "is":{
                    $fName="is_".$sParts[2];
                    $result=$fName($curValue);
                }break;
                case "!=":{
                    $result=($curValue!=$sParts[2]);
                }break;
                case "==":{
                    $result=($curValue==$sParts[2]);
                }break;
                case ">":{
                    $result=($curValue>$sParts[2]);
                }break;
                case "<":{
                    $result=($curValue<$sParts[2]);
                }break;
            }
            if($negated)
                $result=!$result;
            if(!$result)
                return false;
        }
        return true;
    }
}
