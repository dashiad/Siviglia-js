<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 3:48
 */

namespace lib\output;


abstract class Datasource
{
    var $definition;
    var $params;
    var $request;

    function __construct($definition,$params,\Request $request)
    {
        $this->definition = $definition;
        $this->params = $params;
        $this->request = $request;
    }
    function getDataSource()
    {
        $obj=$this->definition["MODEL"];
        $name=$this->definition["NAME"];

        $ds=\lib\datasource\DataSourceFactory::getDataSource($obj,$name);
        $dsDefinition=$obj->getOriginalDefinition();
        if (isset($this->definition["FILTERING_DATASOURCES"]))
            $ds->setFilteringDatasources($this->definition["FILTERING_DATASOURCES"]);
        if($this->params)
        {
            if(is_array($this->params))
            {
                foreach($this->params as $key=>$value)
                {
                    if(isset($dsDefinition["PARAMS"][$key]))
                        $ds->{$key}=$value;
                }
            }
        }
        return $ds;
    }
    function getRole()
    {
        return $this->definition["ROLE"];
    }

    function getData($ds)
    {
        $it=$ds->fetchAll();

        if($this->getRole()=="view")
            $data=$it->getFullRow();
        else
            $data=$it->getFullData();
        return $data;
    }

    function getMetaData()
    {

        include_once(LIBPATH . "/reflection/Meta.php");
        $oMeta = new \DataSourceMetaData($this->object, $this->name);
        return $oMeta->definition;
    }

    abstract function resolve();
}



