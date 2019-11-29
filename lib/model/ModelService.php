<?php
/**
 * Class ModelService
 * @package lib\model
 *  (c) Smartclip
 */


namespace lib\model;
use model\reflection\Model;

class ModelServiceException extends BaseException
{
    const ERR_MODEL_PROVIDER_NOT_FOUND=101;
    const TXT_MODEL_PROVIDER_NOT_FOUND="Provider for model {%model%} not found";
}


class ModelService extends \lib\service\Service
{
    static  $packages=[];
    static  $cache=[];
    static function addPackage($instance)
    {
        $path=$instance->getBaseNamespace();
        $path=ModelService::normalizePath($path);
        ModelService::$packages[]=["path"=>$path,"instance"=>$instance,"len"=>strlen($path)];
    }
    static function normalizePath($path)
    {
        $path=str_replace("\\","/",$path);
        if($path[0]=="/")
            $path=substr($path,1);
        return $path;
    }
    static function getPackage($model)
    {
        if(isset(ModelService::$cache[$model]))
            return ModelService::$cache[$model];
        $maxLength=-1;
        $resolver=null;
        $model=ModelService::normalizePath($model);
        for($k=0;$k<count(ModelService::$packages);$k++)
        {
            if(strpos($model,ModelService::$packages[$k]["path"])===0)
            {
                if(ModelService::$packages[$k]["len"]>$maxLength)
                {
                    $maxLength=ModelService::$packages[$k]["len"];
                    $resolver=ModelService::$packages[$k]["instance"];
                }
            }
        }
        if($resolver==null)
            throw new ModelServiceException(ModelServiceException::ERR_MODEL_PROVIDER_NOT_FOUND,["model"=>$model]);
        ModelService::$cache[$model]=$resolver;
        return $resolver;
    }

    static function getModelDescriptor($objectName,$resolver=null)
    {
        if($resolver==null)
            $resolver=ModelService::getPackage($objectName);
        return $resolver->getModelDescriptor($objectName);
    }
    static function getModel($objectName, $serializer = null)
    {
        $resolver=ModelService::getPackage($objectName);
        $objName = ModelService::getModelDescriptor($objectName,$resolver);
        $objName->includeModel();
        //$def=$objName->getDefinition();
        $namespacedName=$objName->getNamespaced();
        $obj=new $namespacedName($serializer);
        return $obj;
    }
    static function includeClass($className)
    {
        $resolver=ModelService::getPackage($className);

        $resolver->includeFile($className);
    }
    static function includeModel($modelName)
    {
        $resolver=ModelService::getPackage($modelName);
        $resolver->includeModel($modelName);
    }
}
