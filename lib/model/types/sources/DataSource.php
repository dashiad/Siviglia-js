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
    function contains($value)
    {
        if($value===null)
            return true;
        $datasource=$this->buildDataSource();
        $f=$this->getValueField();
        $datasource->{$f}=$value;
        $it=$datasource->fetchAll();
        if($it && $it->count()==1)
            return true;
        return false;

    }
    function buildDataSource()
    {
        $actualParams=[];
        if(isset($this->definition["PARAMS"]))
        {

            $ctxStack = new \lib\model\ContextStack();
            // $this->parent apunta al tipo de dato al que pertenece el source.
            // $this->parent->parent apunta al container que contiene al tipo al que pertenece el source.
            $ctx = new \lib\model\BaseObjectContext($this->parent->parent, "#", $ctxStack);

            foreach($this->definition["PARAMS"] as $k=>$v)
            {


                //$source=$this->parent->parent;
                try {
                    $actualParams[$k] = \lib\php\ParametrizableString::getParametrizedString(
                        $v,
                        $ctxStack
                    );
                }catch(\Exception $e)
                {
                    throw new \lib\model\types\sources\SourceException(SourceException::ERR_INVALID_SOURCE,["source"=>$v]);
                }
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
        return $datasource;
    }

    function getData()
    {
        $datasource=$this->buildDataSource();
        $it=$datasource->fetchAll();
        $data=$it->getFullData();
        if(isset($this->definition["PREPEND"]))
            $data=array_merge($this->definition["PREPEND"],$data);
        if(isset($this->definition["APPEND"]))
            $data=array_merge($data,$this->definition["APPEND"]);
        return $data;
    }
}
