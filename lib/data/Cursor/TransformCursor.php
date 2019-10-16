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
    function init($params)
    {
        $this->transforms=$params["transforms"];
        $me=$this;
        $params["callback"]=function($rows) use ($me){return $me->doTransforms($rows);};

        parent::init($params);
    }
    function doTransforms($lines)
    {
        $newLines=[];
        for($j=0;$j<count($lines);$j++)
            $newLines[]=$this->transform($lines[$j]);
        return $newLines;
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
