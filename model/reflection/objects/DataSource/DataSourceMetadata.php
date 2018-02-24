<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:27
 */

namespace model\reflection\DataSource;
include_once(PROJECTPATH."/model/reflection/objects/base/BaseMetadata.php");
use \model\reflection\base\BaseMetadata;

class DataSourceMetadata extends BaseMetadata {
    function __construct($objName,$dsName)
    {
        include_once(LIBPATH."/datasource/DataSourceFactory.php");
        $ds=\lib\datasource\DataSourceFactory::getDataSource($objName,$dsName);
        $this->definition=$ds->getOriginalDefinition();
        // Es un datasource "normal", no multiple
        if(isset($this->definition["FIELDS"]))
        {
            foreach($this->definition["FIELDS"] as $key=>$value)
            {
                $info=$value;
                $type=\lib\model\types\TypeFactory::getType(null,$info);
                $this->definition["FIELDS"][$key]=\lib\model\types\TypeFactory::getTypeMeta(array_merge($info,$type->definition));
            }
            unset($this->definition["STORAGE"]);
            unset($this->definition["PERMISSIONS"]);
        }
        if(isset($this->definition["PARAMS"]))
        {
            foreach($this->definition["PARAMS"] as $key=>$value)
            {
                $info=$value;
                $type=\lib\model\types\TypeFactory::getType(null,$info);
                $this->definition["PARAMS"][$key]=\lib\model\types\TypeFactory::getTypeMeta(array_merge($info,$type->definition));
            }
            unset($this->definition["STORAGE"]);
            unset($this->definition["PERMISSIONS"]);
        }
        if(isset($this->definition["DATASOURCES"]))
        {
            // Es un datasource multiple.Obtenemos las definiciones de cada uno
            // de los datasources internos.
            foreach($this->definition["DATASOURCES"] as $key=>$value)
            {
                $newDef=new DataSourceMetaData($value["OBJECT"],$value["DATASOURCE"]);
                $this->definition["DATASOURCES"][$key]=$newDef->definition;
            }
        }
    }
}
