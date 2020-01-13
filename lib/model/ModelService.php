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
    const ERR_UNKNOWN_PACKAGE=102;
    const TXT_MODEL_PROVIDER_NOT_FOUND="Provider for model [%model%] not found";
    const TXT_UNKNOWN_PACKAGE="Package [%package%] not found";
}


class ModelService extends \lib\service\Service
{
    static $initialized=false;

    static  $packages=[];
    static  $cache=[];
    function initialize()
    {
        if(ModelService::$initialized==true)
            return;
        global $Config;
        $packages=$Config["PACKAGES"];
        foreach($packages as $k=>$v)
        {
            $fileName=PROJECTPATH."/".$v["path"]."/".str_replace('\\','/',$v["namespace"])."/Package.php";
            include_once($fileName);
            $className=$v["namespace"]."\Package";
            $pkg=new $className($v["namespace"],$v["path"]);
            $this->addPackage($pkg);
        }
    }
    static function addPackage($instance)
    {
        $path=$instance->getBaseNamespace();
        $name=$instance->getName();
        $p = ModelService::normalizePath($path);
        ModelService::$packages[$name] = ["path" => $p, "instance" => $instance, "len" => strlen($p)];

    }
    static function getPackageNames()
    {
        return array_keys(ModelService::$packages);
    }

    static function getPackages()
    {
        return ModelService::$packages;
    }
    static function normalizePath($path)
    {
        $path=str_replace("\\","/",$path);
        if($path[0]=="/")
            $path=substr($path,1);
        return $path;
    }
    static function getPackageByName($name)
    {
        if(!isset(ModelService::$packages[$name]))
            throw new ModelServiceException(ModelServiceException::ERR_UNKNOWN_PACKAGE,array("package"=>$name));
        return ModelService::$packages[$name]["instance"];
    }
    static function getPackage($model)
    {
        if(isset(ModelService::$cache[$model]))
            return ModelService::$cache[$model];
        $model=ltrim($model,"/");
        $maxLength=-1;
        $resolver=null;
        $model=ModelService::normalizePath($model);
        foreach(ModelService::$packages as $k=>$v)
        {
            $path=$v["path"];
            if(strpos($model,$path)===0)
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
    static function loadModel($objectName,$fields,$serializer=null)
    {
        $ins=ModelService::getModel($objectName,$serializer);
        foreach($fields as $k=>$v)
            $ins->{$k}=$v;
        $ins->loadFromFields();
        return $ins;
    }
    static function getDataSource($objectName,$datasource,$serializer=null)
    {
        return \lib\datasource\DataSourceFactory::getDataSource($objectName,$datasource,$serializer);

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
