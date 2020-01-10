<?php


namespace lib\model\types\sources;

/*
 *   Definition:
 *   "SOURCE":[
 *      "TYPE":"DataSource",
 *      "MODEL":<Model>
 *      "DATASOURCE":<datasource>,
 *      "PARAMS":<field>
 *   ]
 *
 */

class DataSource extends BaseSource
{
    function getData()
    {
        $actualParams=[];
        if(isset($this->definition["PARAMS"]))
        {
            foreach($this->definition["PARAMS"] as $k=>$v)
            {
                // $this->parent apunta al tipo de dato al que pertenece el source.
                // $this->parent->parent apunta al container que contiene al tipo al que pertenece el source.
                if($this->useValidatingData)
                    $source=$this->parent->parent->getValidatingValue();
                else
                    $source=$this->parent->parent;
                $actualParams[$k]=\lib\php\ParametrizableString::getParametrizedString(
                    $v,
                    $source
                );
            }
        }
        $ser=null;
        if(isset($this->definition["SERIALIZER"]))
        {
            $ser=\Registry::getService("storage")->getSerializerByName($this->definition["SERIALIZER"]);
        }
        $datasource=\lib\datasource\DataSourceFactory::getDataSource($this->definition["MODEL"],$this->definition["DATASOURCE"],$ser);
        foreach($actualParams as $k=>$v)
        {
            $datasource->{$k}=$v;
        }
        $it=$datasource->fetchAll();
        return $it->getFullData();
    }
}
