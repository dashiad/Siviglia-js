<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 23/10/2017
 * Time: 2:10
 */

namespace lib\output\json;


class JsonDataSource extends \lib\output\Datasource
{
    function resolve()
    {
        $result=array();
        $ds=$this->getDataSource();
        $result["count"]=$ds->count();
        $result["result"]=1;
        $result["error"]=0;
        $it=$ds->fetchAll();
        if($this->getRole()=="view") {
            $data=$it->getFullRow();
            if(!$data)
            {
                $result["data"]=null;
                $result["error"]=1;
                $result["result"]=0;
                $result["message"]="Object not found";
                $result["count"]=0;
            }
            else
                $result["data"]=$data;
        }
        else
            $result["data"]=$it->getFullData();

        if(!is_a($ds,'\lib\datasource\MultipleDataSource'))
        {
            $result["start"]=$ds->getStartingRow();
            $result["end"]=$result["start"]+$it->count();
        }
        $response=\Registry::$registry["response"];
        $response->setBuilder(function() use ($result){ return json_encode($result);});
    }
}

