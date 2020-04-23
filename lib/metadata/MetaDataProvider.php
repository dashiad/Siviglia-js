<?php
namespace lib\metadata;


use lib\model\types\BaseTypeException;

class MetaDataProvider
{
    const META_MODEL=1;
    const META_DATASOURCE=2;
    const META_ACTION=3;
    const META_TYPE=4;
    const META_FORM=5;
    const META_PAGE=6;
    const META_PACKAGE=7;
    const META_TYPE_JS=8;
    const META_FORM_JS=9;
    const META_OTHER=100;

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
        $callbacks=["","Model","Datasource","Action","Type","Form","Page","Package","TypeJs","FormJs","Other"];
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

    // Mostrar listado de todos los Datasources en formato JSON
    function getDatasource($target,$modelName,$targetName,$field,$mode)
    {
        if ($target !== MetaDataProvider::GET_LIST) {
            $ds = \lib\datasource\DataSourceFactory::getDataSource($modelName, $targetName);
            if($target==MetaDataProvider::GET_DEFINITION || $target==MetaDataProvider::GET_FIELD) {
                $def=$ds->getOriginalDefinition();
                if($target==MetaDataProvider::GET_FIELD)
                {
                    return $def["FIELDS"][$field];
                }
                else
                    return $def;

            }
            if($target==MetaDataProvider::GET_PARAM_DEFINITION || $target==MetaDataProvider::GET_PARAM) {
                $params=$ds->getParametersInstance();
                return $this->getBaseTypedObjectMeta($params, $target == MetaDataProvider::GET_DEFINITION ? null : $field, $mode);
            }
        }else
        {
            // constante Package::DATASOURCE
            return $this->genericGetInfo ($modelName, Package::DATASOURCE);
        }
    }

    // Mostrar listado de todos los Forms en formato JSON
    function getForm($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $f=\lib\output\html\Form::getForm($modelName,$targetName,null,null);
            return $this->getBaseTypedObjectMeta($f,$target==MetaDataProvider::GET_DEFINITION?null:$field,$mode);
        }else
        {
            // constante Package::HTML_FORM
            return $this->genericGetInfo ($modelName, Package::HTML_FORM);
        }
    }

    function getAction($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST) {
            $a=\lib\action\Action::getAction($modelName,$targetName);
            return $this->getBaseTypedObjectMeta($a,$target==MetaDataProvider::GET_DEFINITION?null:$field,$mode);
        }
    }
    function getType($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST){
            $targetType=\lib\model\types\TypeFactory::getType(null,$targetName);
            if(!is_a($targetType,'\lib\model\types\BaseType'))
                return null;
            return $targetType->getDefinition();
        }
    }

    function getTypeJs($target,$modelName,$targetName,$field,$mode)
    {
        if($target!==MetaDataProvider::GET_LIST){
            $parts=explode('\\',$field);
            $n=count($parts);
            if($n==0)
            {
                // Si no es un tipo custom, que hacemos aqui??
                return "";
            }
            array_shift($parts);
            $typeName=array_pop($parts);
            if(array_pop($parts)=="types")
            {
                $s=\Registry::getService("model");
                $md=$s->getModelDescriptor('\\'.implode('\\',$parts));

                if($md)
                {
                    $target=$md->getTypeJs($typeName);
                    if($target!==null)
                    {
                        return ["type"=>"class","content"=>file_get_contents($target)];
                    }
                    $instance=\lib\model\types\TypeFactory::getType(null,$field);
                    if($instance)
                    {
                        $meta=$instance->getDefinition();
                        return ["type"=>"definition","content"=>$meta];
                    }

                }
            }
            return null;
        }
    }
    function getTypeMeta($typeName)
    {
        $parts=explode('\\',$typeName);
        $n=count($parts);
        if($n==0)
        {
            // Si no es un tipo custom, que hacemos aqui??
            return "";
        }
        array_shift($parts);
        $typeName=array_pop($parts);
        if(array_pop($parts)=="types") {
            $s = \Registry::getService("model");
            $md = $s->getModelDescriptor('\\' . implode('\\', $parts));

        }

    }

    function getFormJs($target,$modelName,$targetName,$field,$mode)
    {
        $s=\Registry::getService("model");
        $md=$s->getModelDescriptor($modelName);
        $mustRebuild=true;
        $fileJs=$md->getDestinationFile("js/jQuery/actions/$targetName.js");
        $fileHtml=$md->getDestinationFile("js/jQuery/actions/$targetName.html");
        if(!is_file($fileJs) || !is_file($fileHtml))
        {

        }
        return ["template"=>file_get_contents($fileHtml),"js"=>file_get_contents($fileJs)];

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


    /*  Funcion genérica para obtener el GetInfo, cuando se cumpla:
    *        $target == MetaDataProvider::GET_LIST
    *    @param modelName y constante existente lib\model\Package.php
    *    @return getInfo() de lib\model\Package.php
    */
    function genericGetInfo($modelName, $resourceType)
    {
        $s=\Registry::getService("model");              // servicio del modelo
        $package=$s->getPackage($modelName);            // paquete
        $md=$package->getModelDescriptor($modelName);   // nombre del modelo
        $packageName = $package->getName();             // nombre del paquete

        // obtenemos los valores del submodel y className
        if ($md->isPrivate())
        {
            $submodel = $md->getClassName();
            $className = $md->getNamespaceModel();
        }
        else
        {
            $className = $md->getClassName();
            $submodel = null;
        }

        // Llamada a la funcion getInfo con todos los parámetros
        $info = Package::getInfo($packageName, $className, $submodel, $resourceType,"*");
        return $info;
    }

}
