<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/02/2018
 * Time: 16:14
 */

namespace model\reflection;


class ArrayDefinition
{

    function getDefinitions($params)
    {
        $allFiles=\lib\php\FileTools::getFilesInDirectory(__DIR__."/definitions",false,array("json"),true);
        $baseNames=array();
        if($allFiles > 0) {
            $baseNames = array_map(function ($it) {
                return array("name"=>substr($it, 0, strrpos($it, ".")));
            }, $allFiles);
        }
        return $baseNames;
    }
    function getDefinition($param)
    {

    }
}