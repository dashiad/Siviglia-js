<?php


namespace lib\metadata;


class MetaDataProvider
{
    const META_MODEL=1;
    const META_DATASOURCE=2;
    const META_ACTION=3;
    const META_TYPE=4;
    const META_FORM=5;
    const META_PAGE=6;
    const META_PACKAGE=7;
    const META_OTHER=999;
    const GET_DEFINITION=1;
    const GET_FIELD=2;
    const GET_LIST=3;
    const MODE_PLAIN=1;
    const MODE_RESOLVED=2;

    function __construct()
    {

    }
    function getMetaData($type,$target,$name,$field=null,$mode=MetaDataProvider::MODE_PLAIN)
    {
        $callbacks=["","Model","Datasource","Action","Type","Form","Page","Other"];
        return call_user_func(array($this,"get".$callbacks[$type]),$target,$name,$field,$mode);
    }
    function getModel($target,$name,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $s = \Registry::getService("model");
            $m = $s->getModel($name);
            if ($target == MetaDataProvider::GET_DEFINITION)
                $out=$m->getDefinition();
            else
                $out=$m->__getField($field)->getType()->getDefinition();
        }
        return $out;
    }
}