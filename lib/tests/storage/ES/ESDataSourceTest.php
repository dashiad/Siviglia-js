<?php
namespace lib\tests\storage\ES;
$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;


class ESDataSourceTest extends TestCase
{
    var $serializer = null;

    function getSimpleDefinedObject()
    {
        return new \lib\model\BaseTypedObject([
            "INDEXFIELDS"=>["id"],
            "FIELDS"=>[
                "id"=>["TYPE"=>"Integer"],
                "Date"=>["TYPE"=>"Date"],
                "Ad_unit"=>["TYPE"=>"String"],
                "Unified_pricing_rule"=>["TYPE"=>"String"],
                "Ad_unit_ID"=>["TYPE"=>"Integer"],
                "Unified_pricing_rule_ID"=>["TYPE"=>"Integer"],
                "Total_impressions"=>["TYPE"=>"Integer"],
                "Total_CTR"=>["TYPE"=>"Decimal","NINTEGERS"=>1,"NDECIMALS"=>5],
                "Ad_Exchange_impressions"=>["TYPE"=>"Integer"],
                "Ad_Exchange_revenue"=>["TYPE"=>"Decimal","NINTEGERS"=>8,"NDECIMALS"=>5]

            ]
        ]);
    }
    function getDefaultSerializer()
    {
        global $Config;
        $sc=$Config["SERIALIZERS"]["es"];
        if($this->serializer===null)
            $this->serializer=new \lib\storage\ES\ESSerializer($sc);
        return $this->serializer;
    }

    function createTestIndex($obj)
    {
        $ser=$this->getDefaultSerializer();
        try{
            $ser->destroyStorage($obj);
        }catch(\Exception $e)
        {

        }
        $ser->createStorage($obj);

        $op=fopen("sampleData.csv","r");
        $n=0;
        $keys=array();
        $allrows=[];
        $id=0;
        while($row=fgetcsv($op))
        {

            if($n==0) {
                $keys = array_values($row);
                $n++;
                continue;
            }
            $ins=$this->getSimpleDefinedObject();
            $id++;
            $ins->id=$id;

            $data=array_combine($keys,$row);

            $d=\DateTime::createFromFormat("m/d/y",$data["Date"]);
            $ins->Date=$d->format('Y-m-d');


            $ins->Ad_unit=$data["Ad_unit"];
            $ins->Unified_pricing_rule=$data["Unified_pricing_rule"];
            $ins->Ad_unit_ID=$data["Ad_unit_ID"];
            $ins->Unified_pricing_rule_ID=$data["Unified_pricing_rule_ID"];
            $ins->Total_impressions=str_replace([".",","],"",$data["Total_impressions"]);
            $ins->Total_CTR=str_replace("%","",$data["Total_CTR"]);
            $ins->Ad_Exchange_impressions=str_replace(",","",$data["Ad_Exchange_impressions"]);
            $ins->Ad_Exchange_revenue=$data["Ad_Exchange_revenue"];
            $allrows[]=$ins;
        }
        $this->serializer->add($allrows);
    }

    function getDatasource1()
    {
        return new \lib\storage\ES\ESDataSource(
            null,
            "Simple",
            [
                "PARAMS"=>[
                    "Ad_unit"=>array(

                    )
                ]
            ],
            $this->serializer
        );
    }
}
