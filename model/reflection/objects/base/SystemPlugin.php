<?php
namespace model\reflection\base;
class SystemPlugin {

    function __filterFieldsBy($tableName,$attrName,$attrValue)
    {
        global $objectDefinitions;
        $results=array();
        $fNames=$this->getFieldNames($tableName);
        for($k=0;$k<count($fNames);$k++)
        {

            if($objectDefinitions[$tableName]["FIELDS"][$fNames[$k]][$attrName]==$attrValue)
                $results[]=$fNames[$k];
        }
        return $results;
    }
    function iterateOnModels($method)
    {
        global $Config;
        $packages=$Config["PACKAGES"];
        for($k=0;$k<count($packages);$k++)
        {
            $curPackage=$packages[$k];
            $package=new \model\reflection\Package($curPackage);
            $models=$package->getModels();
            foreach($models as $name=>$model)
            {
                $this->{$method}($package,$name,$model);
            }
        }
    }
    function getLayer($layer)
    {
        return ReflectorFactory::getLayer($layer);
    }

    // Metodo para capturar eventos que no usamos
    function __call($method,$args)
    {

    }


}
?>
