<?php
/**
 * Class DefaultModelProvider
 * @package lib\model
 *  (c) Smartclip
 */


namespace lib\model;


abstract class PlainPackage extends \lib\model\Package
{
    public function includeFile($className)
    {
        $fileName=$this->basePath."/".str_replace('\\','/',$className).".php";
        include_once($fileName);
    }



}
