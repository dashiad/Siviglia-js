<?php
namespace lib\output\html\renderers\js;
class BaseJsRenderer
{
    function __construct($regexes)
    {
        $this->regexes=$regexes;
    }
    function resolve($path,$parts)
    {
        foreach($this->regexes as $key=>$value)
        {
            if(preg_match($key,$path,$matches))
            {
                $modelName=$matches[1];
                $path=str_replace("#1#",$matches[2],$value["regex"]);
                $filePath=$this->getTargetFile($modelName,$path);
                $fileType=$value["type"];
                $callbackName="on".ucfirst(strtoupper($fileType));
                if(method_exists($this,$callbackName))
                    return $this->{$callbackName}($filePath);
                if(!is_file($filePath))
                {
                    \lib\Response::generateError();
                    die();
                }
                $op=fopen($filePath,"r");
                fpassthru($op);
                fclose($op);
                die();
            }
        }
    }
    function getTargetFile($modelName,$path)
    {
        $s=\Registry::getService("model");
        $inst=$s->getModelDescriptor($modelName);
        return $inst->getDestinationFile($path);
    }
}
