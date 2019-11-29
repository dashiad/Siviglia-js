<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 04/10/2016
 * Time: 18:19
 */

namespace lib\data\Cursor;
include_once(PROJECTPATH."/lib/php/ParametrizableString.php");


class TransformCursor extends Cursor
{
    var $transforms;
    var $sampleModel=null;
    var $typeSerializer=null;
    var $serializers=null;
    var $types=null;
    var $outputFields=null;
    function init($params)
    {
        $this->transforms=$params["transforms"];
        $me=$this;
        $params["callback"]=function($rows) use ($me){return $me->doTransforms($rows);};
        $this->useTypeMap=false;
        if(isset($params["typeMap"]))
        {
            $this->useTypeMap=true;
            $this->metadata["model"]=$params["typeMap"]["model"];
            $this->setMetaData($this->metadata);
            $modelService=\Registry::getService("model");
            $this->sampleModel=$modelService->getModel($params["typeMap"]["model"]);
            $storageService=\Registry::getService("storage");
            $this->typeSerializer=$storageService->getSerializerByName($params["typeMap"]["serializer"]);
        }
        parent::init($params);
    }
    function doTransforms($lines)
    {
        $newLine=$this->transform($lines);
        return $newLine;
    }
    function transform($line)
    {
        foreach($line as $key=>$value)
        {
            if(isset($this->transforms[$key])) {
                $d = $this->transforms[$key];
                if (isset($d["value"]))
                    $line[$key] = $this->transformValue($d["value"], $value, $line);
                if(isset($d["generate"]))
                    $line=$this->generateFields($d["generate"],$value,$line);
            }

        }
        if(isset($this->transforms["*"]))
        {
            if(isset($this->transforms["*"]["generate"]))
            {
                    $line=$this->generateFields($this->transforms["*"]["generate"],'',$line);
            }
        }
        $line=$this->mapColumnNames($line);

        if($this->typeSerializer!==null)
        {
            if($this->serializers===null)
                $this->serializers=$this->typeSerializer->getSerializersForObject($this->sampleModel);
            $line=$this->typeSerializer->mapIncomingColumns($line);
            foreach($line as $k=>$v)
            {
                $this->serializers[$k]->unserialize($k,$this->sampleModel->{"*".$k},$line,$this->typeSerializer,$this->sampleModel);

                $result[$k]=$this->sampleModel->{$k};
            }
            $dirty=$this->sampleModel->getDirtyFields();
            $nDirty=count($dirty);
            if($nDirty > 0) {
                $fields=array_keys($dirty);
                for ($k = 0; $k < $nDirty; $k++) {
                    $result[$fields[$k]] = $this->sampleModel->{$fields[$k]};
                }
            }
            $line=$result;
        }
        return $line;
    }
    function transformValue($def,$value,$line)
    {
        if($def["method"])
            return $this->{$def["method"]}($value,$line);
        if($def["paramString"])
        {
            return \lib\php\ParametrizableString::getParametrizedString($def["paramString"],$line);
        }
    }
    function generateFields($def,$value,$line)
    {

        foreach($def as $k=>$v)
        {
            if(isset($v["method"])) {
                $line=call_user_func(array($this,$v["method"]),$line);
                continue;
            }
            if(isset($v["regexp"]))
            {
                if(is_array($v["regexp"])) {
                    $reg = $v["regexp"]["reg"];
                    if(isset($v["regexp"]["val"]))
                        $dest=$v["regexp"]["val"];
                    else
                        $dest="\$1";
                    $default=$v["regexp"]["default"];
                }
                else {
                    $reg = $v["regexp"];
                    $dest = "\$1";
                    $default="";
                }
                if(preg_match($reg,$value,$matches))
                {
                    for($j=1;$j<count($matches);$j++)
                        $dest=str_replace("\$".$j,$matches[$j],$dest);
                    $line[$k]=$dest;
                }
                else
                    $line[$k]=$default;
            }
            else
                $line[$k]=$v["value"];
        }
        return $line;
    }




}
