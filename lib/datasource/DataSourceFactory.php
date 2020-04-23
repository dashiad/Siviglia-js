<?php
namespace {
include_once(PROJECTPATH."/lib/datasource/DataSource.php");

function getDataSource($objName,$dsName,$serializer=null)
{
    return \lib\datasource\DataSourceFactory::getDataSource($objName,$dsName,$serializer);
}
function applyDataSource($objName,$dsName,$params,$callable,$dsParams=null,$serializer=null)
{
    return \lib\datasource\DataSourceFactory::applyDataSource($objName,$dsName,$params,$callable,$dsParams,$serializer);
}

}
namespace lib\datasource {
    class DataSourceFactoryException extends \lib\model\BaseException
    {
        const ERR_NO_APPLICABLE_SOURCE=1;
        const TXT_NO_APPLICABLE_SOURCE="No encontrado source disponible para el datasource [%dsname%] de [%object%]";
    }
class DataSourceFactory
{
        static function getDataSource($objName,$dsName,$serializer=null)
        {
            $objNameClass=\lib\model\ModelService::getModelDescriptor($objName);
            require_once($objNameClass->getDataSourceFileName($dsName));
            $objName=$objNameClass->getNamespaced();
            $csN=$objName.'\datasources\\'.$dsName;

            if(!class_exists($csN))
            {
                throw new DataSourceException(DataSourceException::ERR_NO_SUCH_DATASOURCE,array("object"=>$objName,"datasource"=>$dsName));
            }
            $instance=new $csN();
            if(is_a($instance,'\lib\datasource\MultipleDatasource'))
                return $instance;

            $mainDef=$csN::$definition;
            if(isset($mainDef["ROLE"]) && $mainDef["ROLE"]=="MULTIPLE")
            {
                return new \lib\datasource\MultipleDatasource($objName,$dsName,$mainDef,$serializer);
            }

            // Si se nos pasa un serializer, se busca un SOURCE de tipo STORAGE cuyo tipo coincida con el del serializer.
            $serType=null;
            $serName=null;
            if($serializer)
            {
                $serType=$serializer->getSerializerType();
            }
            else
            {

                // Si no hay un serializer, y hay un STORAGE, el serializador se obtiene del modelo.
                if(isset($mainDef["SOURCE"]["STORAGE"]))
                {
                    $keys=array_keys($mainDef["SOURCE"]["STORAGE"]);
                    $firstKey=$keys[0];
                    $serName=$firstKey; // En realidad no sabemos si esto es el nombre de un serializador, o de un tipo de serializador..
                    // Deberia ser de un serializador, no de un tipo.
                    $serializerService=\Registry::getService("storage");
                    try{
                        $serializer=$serializerService->getSerializerByName($firstKey);
                        if($serializer)
                            $serType=$serializer->getSerializerType();
                    }catch(\Exception $e)
                    {
                        // TODO : ver si aqui necesitamos relanzar la excepcion.
                    }
                    if($serType===null) {
                        $modelService = \Registry::getService("model");
                        $modelIns = $modelService->getModel($objName);
                        $serializer = $modelIns->__getSerializer();
                        $serType = ucfirst(strtolower($serializer->getSerializerType()));
                    }
                }
                // TODO : Datasource mal formado
            }

            if($serType!==null) {
                $uSerType = ucfirst(strtolower($serType));
                $options = null;

                if($serName!==null && isset($mainDef["SOURCE"]["STORAGE"][$serName])) {
                    $options = $mainDef["SOURCE"]["STORAGE"][$serName];

                }
                else
                {
                    $options=$mainDef["SOURCE"]["STORAGE"][strtoupper($serType)];
                }
                if($options){

                    $dsN = '\\lib\\storage\\' . $uSerType . '\\' . $uSerType . 'DataSource';
                    $mainDs = new $dsN($objName, $dsName, $instance, $serializer, $options);
                    return $mainDs;
                }
            }
            // Si no hay nada relacionado con serializadores, se busca el primer "otro" source que haya.
            foreach($mainDef["SOURCE"] as $k=>$v)
            {
                if($k=="STORAGE")
                    continue;
                $dsHandler=ucfirst(strtolower($k));
                $dsN='\lib\datasource\\'.$dsHandler.'DataSource';
                $mainDs = new $dsN($objName, $dsName, $instance, null, $options);
                return $mainDs;
            }
            // Y, si no hay nada, lanzamos excepcion
            throw new DataSourceFactoryException(DataSourceFactoryException::ERR_NO_APPLICABLE_SOURCE,["object"=>$objName,"dsname"=>$dsName]);


        }
        function applyDataSource($objName,$dsName,$params,$callable,$dsParams=null,$serializer=null)
        {
            $ds=\getDataSource($objName,$dsName);
            if(is_array($params))
            {
                foreach($params as $key=>$value)
                    $ds->{$key}=$value;
            }
            if($dsParams)
            {
                $pagingParameters=$ds->getPagingParameters();
                foreach($dsParams as $key=>$value)
                    $pagingParameters->{$key}=$value;
            }
            $ds->fetchAll();
            $n=$ds->count();
            $it=$ds->getIterator();
            for($k=0;$k<$n;$k++)
                $callable($it[$k],$k,$n,$ds);
        }
}
}

