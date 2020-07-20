<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 15:09
 */

namespace lib\routing;


class Meta
{
    var $request;
    var $definition;
    var $params;


    function __construct($def,$params,$request)
    {
        $this->request=$request;
        $this->definition=$def;
        $this->params=$params;
    }
    function resolve()
    {
        $response=\Registry::$registry["response"];
        $params=$this->params;
        if(isset($params["multi"]))
        {
            $reqs=json_decode($params["multi"],true);
        }
        else
        {
            $reqs=array(array("type"=>ucfirst($params["type"]),"name"=>$params["name"],"model"=>$params["model"]));
        }

        for($k=0;$k<count($reqs);$k++)
        {
            $c=$reqs[$k];
            $obj=null;
            switch($c["type"])
            {
                case "Model":
                {
                    $obj=\model\reflection\Model::getMetaData(trim($c["model"],". "));
                }break;
                case "Datasource":
                {
                    $obj=\model\reflection\DataSource::getMetaData(trim($c["model"],". "),trim($c["name"],". "));

                }break;
                case "Form":
                {
                    include_once(PROJECTPATH."/model/reflection/objects/Html/forms/FormDefinition.php");
                    $obj=\model\reflection\Html\forms\FormDefinition::getMetaData(trim($c["model"],". "),trim($c["name"],". "));

                }break;
            }
            if($obj)
            {
                $c["definition"]=$obj->definition;
                $results[]=$c;
            }
        }
        $m=$this;
        $response->setBuilder(function () use ($m,$results) {
            echo json_encode(array("error"=>0,"data"=>$results));
        });


        //$this->onError($response);

    }
}



