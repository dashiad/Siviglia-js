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
    const META_OTHER=8;
    const GET_DEFINITION=1;
    const GET_FIELD=2;
    const GET_LIST=3;
    const GET_PARAM=4;
    const GET_PARAM_DEFINITION=5;
    const MODE_PLAIN=1;
    const MODE_RESOLVED=2;

    function __construct()
    {

    }
    // type: una constante META_xxx
    // target: una constante GET_xxx
    // modelName: nombre del modelo.
    // targetName: Si el target no es el propio modelo, el nombre del target (el nombre del datasource, form, page, etc).
    // field: Si hay que devolver un campo (GET_FIELD; GET_PARAM), el campo a devolver.
    // mode: Si hay que devolver definiciones con referencias (model=>"..",field=>".."), o con las referencias resueltas.
    function getMetaData($type,$target,$modelName,$targetName=null,$field=null,$mode=MetaDataProvider::MODE_PLAIN)
    {
        $callbacks=["","Model","Datasource","Action","Type","Form","Page","Package","Other"];
        return call_user_func(array($this,"get".$callbacks[$type]),$target,$modelName,$targetName,$field,$mode);
    }
    function getBaseTypedObjectMeta($baseTypedObject,$field=null,$mode=MetaDataProvider::MODE_PLAIN)
    {
        if($field==null)
            return $baseTypedObject->getDefinition();
        return $baseTypedObject->__getField($field)->getType()->getDefinition();

    }
    function getModel($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $s = \Registry::getService("model");
            $m = $s->getModel($modelName);
            return $this->getBaseTypedObjectMeta($m,$target==MetaDataProvider::GET_DEFINITION?null:$field,$mode);
        }
    }

    function getDatasource($target,$modelName,$targetName,$field,$mode)
    {
        if ($target !== MetaDataProvider::GET_LIST) {
            $ds = \lib\datasource\DataSourceFactory::getDataSource($modelName, $targetName);
            if($target==MetaDataProvider::GET_DEFINITION || $target==MetaDataProvider::GET_FIELD)
                return $this->getBaseTypedObjectMeta($ds, $target == MetaDataProvider::GET_DEFINITION ? null : $field, $mode);
            if($target==MetaDataProvider::GET_PARAM_DEFINITION || $target==MetaDataProvider::GET_PARAM) {
                $params=$ds->getParametersInstance();
                return $this->getBaseTypedObjectMeta($params, $target == MetaDataProvider::GET_DEFINITION ? null : $field, $mode);
            }
        }
    }
    function getForm($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $f=\lib\output\html\Form::getForm($modelName,$targetName,null,null);
            return $this->getBaseTypedObjectMeta($f,$target==MetaDataProvider::GET_DEFINITION?null:$field,$mode);
        }
    }
    function getAction($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $a=\lib\action\Action::getAction($modelName,$targetName);
            return $this->getBaseTypedObjectMeta($a,$target==MetaDataProvider::GET_DEFINITION?null:$field,$mode);
        }
    }

    function validate($type,$modelName,$targetName,$field,$path,$value)
    {
        switch($type)
        {
            case MetaDataProvider::META_FORM:{
                $formField=$this->getForm(MetaDataProvider::GET_FIELD,$modelName,$targetName,$field,MetaDataProvider::MODE_RESOLVED);
                $type=\lib\model\types\TypeFactory::getType(null,$formField);
                if($path!="")
                    $type=$type->getTypeFromPath($path);
                $type->validate($value);
            }break;
        }
    }



}
