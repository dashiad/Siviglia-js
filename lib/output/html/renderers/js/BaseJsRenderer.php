<?php
namespace lib\output\html\renderers\js;
use Registry;

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

                if($value["root"]=="model") {
                    $modelName = $matches[1];
                    $filePath=null;
                    $fileType = $value["type"];
                    if($value["regex"]!=null) {
                        $path = str_replace("#1#", $matches[2], $value["regex"]);
                        $filePath = $this->getTargetFile($modelName, $path);
                    }
                    $callbackName = "on" . ucfirst(strtolower($fileType));
                    if (method_exists($this, $callbackName))
                        return $this->{$callbackName}($modelName,$filePath,$matches);
                }
                else
                {
                    $currentSite=Registry::getService("site")->getCurrentWebsite();
                    $root=$currentSite->getWidgetPath();
                    $filePath=PROJECTPATH.$root[0].str_replace("#1#", $matches[1], $value["regex"]);
                }
            if (!is_file($filePath)) {
                \lib\Response::generateError();
                die();
            }
            $op = fopen($filePath, "r");
            fpassthru($op);
            fclose($op);
            return;
            }
        }
        \lib\Response::generateError();
        die();
    }

    function getTargetFile($modelName,$path)
    {
        $s=\Registry::getService("model");
        $inst=$s->getModelDescriptor("/model/".$modelName);
        return $inst->getDestinationFile($path);
    }
}
