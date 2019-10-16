<?php
/**
 * Class QueryBuilderTest
 * @package lib\tests\storage\ES
 *  (c) Smartclip
 */


namespace lib\tests\storage\ES;
include_once(__DIR__."/../../../../install/config/CONFIG_test.php");
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use lib\model\BaseTypedObject;


class QueryBuilderTest extends TestCase
{
    /**
     *  Test 1: Prueba sin ningun tipo de condicion, solo una serie de campos a devolver.
     */
    const TEST_INDEX_NAME="SampleData";
    var $serializer;
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
        if($this->serializer===null)
            $this->serializer=new \lib\storage\ES\ESSerializer(["NAME"=>"MAIN_ES","ES"=>["servers"=>[ES_TEST_SERVER],"port"=>ES_TEST_PORT,"index"=>QueryBuilderTest::TEST_INDEX_NAME]]);
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

    function testSimple()
    {
        /*
         * "Date"=,"Ad_unit","Unified_pricing_rule","Ad_unit_ID","Unified_pricing_rule_ID",
            "Total_impressions","Total_CTR","Ad_Exchange_impressions"=>["type"=>"long"],
            "Ad_Exchange_revenue"=>["type"=>"float"]
         */
        $sample=$this->getSimpleDefinedObject();
        $this->createTestIndex($sample);
        sleep(1); // Para que se refresque el indice de Elasticsearch
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'BASE'=>['Date','Ad_Unit','Ad_Exchange_impressions']
        );
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,null);
        $t=$qb->build();
        $res=json_encode($t);
            //$client=new \lib\storage\ES\ESClient(["192.168.56.102"]);
            //$res2=$client->query($t);
        $this->assertEquals("{\"index\":\"".QueryBuilderTest::TEST_INDEX_NAME."\",\"_source\":[\"Date\",\"Ad_Unit\",\"Ad_Exchange_impressions\"],\"timeout\":\"30000ms\",\"body\":[]}",
            $res
        );
        $client=$serializer->getConnection();
        $data=$client->query($t);
        $this->assertEquals(193,$data["hits"]["total"]["value"]);
    }
    /**
     *  Test 2: Prueba con una condicion simple.
     */
    function testSingleCondition()
    {

        $params=new BaseTypedObject(
            array(
                "FIELDS"=>array(
                    "Unified_pricing_rule"=>array("TYPE"=>"String")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                'Unified_pricing_rule'=>array("TYPE"=>"String","REQUIRED"=>true)
             ),
            'BASE'=>['Date','Ad_Unit','Ad_Exchange_impressions'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Unified_pricing_rule',
                        'OP'=>'=',
                        'V'=>'{%Unified_pricing_rule%}'
                    ),
                    'FILTERREF'=>'Unified_pricing_rule'
                )
            )
        );

        $params->Unified_pricing_rule="SC_KIBANA_FFLR_2.75EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);

        $this->assertEquals("{\"index\":\"".QueryBuilderTest::TEST_INDEX_NAME."\",\"_source\":[\"Date\",\"Ad_Unit\",\"Ad_Exchange_impressions\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must\":{\"terms\":{\"Unified_pricing_rule\":[\"SC_KIBANA_FFLR_2.75EUR\"]}}}}}}",
            $res
        );
        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);
        $this->assertEquals($data["hits"]["total"]["value"],3);
    }

    /**
     *  Test 2: Prueba con una condicion simple, (negada)
     */
    function testNegatedSingleCondition()
    {

        $params=new BaseTypedObject(
            array(
                "FIELDS"=>array(
                    "Unified_pricing_rule"=>array("TYPE"=>"String")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                'Unified_pricing_rule'=>array("TYPE"=>"String","REQUIRED"=>true)
            ),
            'BASE'=>['Date','Ad_Unit','Ad_Exchange_impressions'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Unified_pricing_rule',
                        'OP'=>'!=',
                        'V'=>'{%Unified_pricing_rule%}'
                    ),
                    'FILTERREF'=>'Unified_pricing_rule'
                )
            )
        );

        $params->Unified_pricing_rule="SC_KIBANA_FFLR_2.75EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);

        $this->assertEquals("{\"index\":\"".QueryBuilderTest::TEST_INDEX_NAME."\",\"_source\":[\"Date\",\"Ad_Unit\",\"Ad_Exchange_impressions\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must_not\":{\"terms\":{\"Unified_pricing_rule\":[\"SC_KIBANA_FFLR_2.75EUR\"]}}}}}}",
            $res
        );
        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);
        $this->assertEquals($data["hits"]["total"]["value"],190);


    }
    /**
     *  Test 4: Prueba con una condicion de rango
     */
    function testRangedSingleCondition()
    {
        /*
         * "Date"=,"Ad_unit","Unified_pricing_rule","Ad_unit_ID","Unified_pricing_rule_ID",
            "Total_impressions","Total_CTR","Ad_Exchange_impressions"=>["type"=>"long"],
            "Ad_Exchange_revenue"=>["type"=>"float"]
         */

        $params=new BaseTypedObject(
            array(
                "FIELDS"=>array(
                    "MIN_IMPRESSIONS"=>array("TYPE"=>"Decimal"),
                    "MAX_IMPRESSIONS"=>array("TYPE"=>"Decimal")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                'MIN_IMPRESSIONS'=>array("TYPE"=>"Decimal","REQUIRED"=>true),
                'MAX_IMPRESSIONS'=>array("TYPE"=>"Decimal","REQUIRED"=>false)
            ),
            'BASE'=>['Total_impressions','Unified_pricing_rule','Date'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'>',
                        'V'=>'{%MIN_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_IMPRESSIONS'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'<=',
                        'V'=>'{%MAX_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_IMPRESSIONS'
                )
            )
        );

        $params->MIN_IMPRESSIONS=100;
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);

 //       $client=new \lib\storage\ES\ESClient(["192.168.56.102"]);
 //       $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Date"],"timeout":"30000ms","body":{"query":{"bool":{"must":{"range":{"Total_impressions":{"gt":100}}}}}}}',
            $res
        );
    }

    /**
     *  Test 5: Prueba con una condicion de rango, mas una condicion de terminos.
     */
    function testRangedTermsCondition()
    {
        $params=new BaseTypedObject(

            array(
                "FIELDS"=>array(
                    "MIN_IMPRESSIONS"=>array("TYPE"=>"Integer"),
                    "MAX_IMPRESSIONS"=>array("TYPE"=>"Integer"),
                    'AD_EXCHANGE_PRICING_RULE_NAME'=>array("TYPE"=>"String","REQUIRED"=>true)
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'BASE'=>['AD_EXCHANGE_IMPRESSIONS','AD_EXCHANGE_MATCHED_REQUESTS','AD_EXCHANGE_AD_ECPM','AD_EXCHANGE_PRICING_RULE_NAME'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'>',
                        'V'=>'{%MIN_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_IMPRESSIONS'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'<=',
                        'V'=>'{%MAX_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_IMPRESSIONS'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_PRICING_RULE_NAME',
                        'OP'=>'!=',
                        'V'=>'{%AD_EXCHANGE_PRICING_RULE_NAME%}'
                    ),
                    'TRIGGER_VAR'=>'AD_EXCHANGE_PRICING_RULE_NAME'
                )
            )
        );

        $params->MIN_IMPRESSIONS=20000;
       // $params->MIN_AD_EXCHANGE_AD_ECPM=1;
        $params->AD_EXCHANGE_PRICING_RULE_NAME="SC_KIBANA_FFLR_0.50EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        //$ser=$this->getDefaultSerializer();
        //$client=$ser->getConnection();
        //$res2=$client->query($t);
        $this->assertEquals("{\"index\":\"SampleData\",\"_source\":[\"AD_EXCHANGE_IMPRESSIONS\",\"AD_EXCHANGE_MATCHED_REQUESTS\",\"AD_EXCHANGE_AD_ECPM\",\"AD_EXCHANGE_PRICING_RULE_NAME\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must\":{\"range\":{\"Total_impressions\":{\"gt\":20000}}},\"must_not\":{\"terms\":{\"AD_EXCHANGE_PRICING_RULE_NAME\":[\"SC_KIBANA_FFLR_0.50EUR\"]}}}}}}",
            $res
        );
    }
    // Mismo test anterior, pero hacemos que una condicion de rango no ponga ninguna condicion.
    // El resultado es que el procesador de elementos vacios de QueryBuilder debe eliminar completamente el elemento "must", ya que las condiciones de rango estan vacias.
    function testRangedTermsCondition2()
    {
        $params=new BaseTypedObject(

            array(
                "FIELDS"=>array(
                    "MIN_IMPRESSIONS"=>array("TYPE"=>"Integer"),
                    "MAX_IMPRESSIONS"=>array("TYPE"=>"Integer"),
                    'AD_EXCHANGE_PRICING_RULE_NAME'=>array("TYPE"=>"String","REQUIRED"=>true)
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'BASE'=>['AD_EXCHANGE_IMPRESSIONS','AD_EXCHANGE_MATCHED_REQUESTS','AD_EXCHANGE_AD_ECPM','AD_EXCHANGE_PRICING_RULE_NAME'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'>',
                        'V'=>'{%MIN_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_IMPRESSIONS'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Total_impressions',
                        'OP'=>'<=',
                        'V'=>'{%MAX_IMPRESSIONS%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_IMPRESSIONS'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_PRICING_RULE_NAME',
                        'OP'=>'!=',
                        'V'=>'{%AD_EXCHANGE_PRICING_RULE_NAME%}'
                    ),
                    'TRIGGER_VAR'=>'AD_EXCHANGE_PRICING_RULE_NAME'
                )
            )
        );


        $params->AD_EXCHANGE_PRICING_RULE_NAME="SC_KIBANA_FFLR_0.50EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        //$ser=$this->getDefaultSerializer();
        //$client=$ser->getConnection();
        //$res2=$client->query($t);
        $this->assertEquals("{\"index\":\"SampleData\",\"_source\":[\"AD_EXCHANGE_IMPRESSIONS\",\"AD_EXCHANGE_MATCHED_REQUESTS\",\"AD_EXCHANGE_AD_ECPM\",\"AD_EXCHANGE_PRICING_RULE_NAME\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must_not\":{\"terms\":{\"AD_EXCHANGE_PRICING_RULE_NAME\":[\"SC_KIBANA_FFLR_0.50EUR\"]}}}}}}",
            $res
        );
    }

    function testAggregation()
    {
        $def=array(
            "INDEX"=>"adx_floor_rules",
            'PARAMS'=>array(
                'type'=>array("TYPE"=>"Decimal","REQUIRED"=>true)
            ),
            'BASE'=>['AD_EXCHANGE_IMPRESSIONS','AD_EXCHANGE_MATCHED_REQUESTS','AD_EXCHANGE_AD_ECPM','AD_EXCHANGE_PRICING_RULE_NAME'],
            'GROUPBY'=>"AD_EXCHANGE_PRICING_RULE_NAME => (100)AD_EXCHANGE_IMPRESSIONS => (0.5)AD_EXCHANGE_AD_ECPM"

        );
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,null);
        $t=$qb->build();
        $res=json_encode($t);
//        $client=new \lib\storage\ES\ESClient(["192.168.56.102"]);
//        $res2=$client->query($t);
        $this->assertEquals("{\"index\":\"adx_floor_rules\",\"_source\":[\"AD_EXCHANGE_IMPRESSIONS\",\"AD_EXCHANGE_MATCHED_REQUESTS\",\"AD_EXCHANGE_AD_ECPM\",\"AD_EXCHANGE_PRICING_RULE_NAME\"],\"timeout\":\"30000ms\",\"body\":{\"aggs\":{\"AD_EXCHANGE_PRICING_RULE_NAME\":{\"terms\":{\"field\":\"AD_EXCHANGE_PRICING_RULE_NAME\",\"size\":1000},\"aggs\":{\"AD_EXCHANGE_IMPRESSIONS\":{\"histogram\":{\"field\":\"AD_EXCHANGE_IMPRESSIONS\",\"interval\":\"100\"},\"aggs\":{\"AD_EXCHANGE_AD_ECPM\":{\"histogram\":{\"field\":\"AD_EXCHANGE_AD_ECPM\",\"interval\":\"0.5\"}}}}}}}}}",
            $res
        );
    }

    /**
     *  Test de parametros de paginacion
     */
    function testPaging()
    {
        $params=new BaseTypedObject(

            array(
                "FIELDS"=>array(
                    "MIN_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal")
                )
            )
        );
        $def=array(
            "INDEX"=>"adx_floor_rules",
            'PARAMS'=>array(
                'type'=>array("TYPE"=>"Decimal","REQUIRED"=>true)
            ),
            'BASE'=>['AD_EXCHANGE_IMPRESSIONS','AD_EXCHANGE_MATCHED_REQUESTS','AD_EXCHANGE_AD_ECPM','AD_EXCHANGE_PRICING_RULE_NAME'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_AD_ECPM',
                        'OP'=>'>',
                        'V'=>'{%MIN_AD_EXCHANGE_AD_ECPM%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_AD_EXCHANGE_AD_ECPM'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_AD_ECPM',
                        'OP'=>'<=',
                        'V'=>'{%MAX_AD_EXCHANGE_AD_ECPM%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_AD_EXCHANGE_AD_ECPM'
                )
            ),
            "LIMIT"=>100
        );

        $params->MAX_AD_EXCHANGE_AD_ECPM=2;
        $params->MIN_AD_EXCHANGE_AD_ECPM=1;
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
    //           $client=new \lib\storage\ES\ESClient(["192.168.56.102"]);
    //           $res2=$client->query($t);
        $this->assertEquals("{\"index\":\"adx_floor_rules\",\"_source\":[\"AD_EXCHANGE_IMPRESSIONS\",\"AD_EXCHANGE_MATCHED_REQUESTS\",\"AD_EXCHANGE_AD_ECPM\",\"AD_EXCHANGE_PRICING_RULE_NAME\"],\"timeout\":\"30000ms\",\"size\":100,\"body\":{\"query\":{\"bool\":{\"must\":{\"range\":{\"AD_EXCHANGE_AD_ECPM\":{\"gt\":1,\"lte\":2}}}}}}}",
            $res
        );
    }
    /**
     *  Test de parametros de paginacion 2, usando STARTINGROW y PAGESIZE
     */
    function testPaging2()
    {
        $params=new BaseTypedObject(

            array(
                "FIELDS"=>array(
                    "MIN_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal")
                )
            )
        );
        $def=array(
            "INDEX"=>"adx_floor_rules",
            'PARAMS'=>array(
                'type'=>array("TYPE"=>"Decimal","REQUIRED"=>true)
            ),
            'BASE'=>['AD_EXCHANGE_IMPRESSIONS','AD_EXCHANGE_MATCHED_REQUESTS','AD_EXCHANGE_AD_ECPM','AD_EXCHANGE_PRICING_RULE_NAME'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_AD_ECPM',
                        'OP'=>'>',
                        'V'=>'{%MIN_AD_EXCHANGE_AD_ECPM%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_AD_EXCHANGE_AD_ECPM'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'AD_EXCHANGE_AD_ECPM',
                        'OP'=>'<=',
                        'V'=>'{%MAX_AD_EXCHANGE_AD_ECPM%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_AD_EXCHANGE_AD_ECPM'
                )
            ),
            "STARTINGROW"=>100,
            "PAGESIZE"=>200
        );

        $params->MAX_AD_EXCHANGE_AD_ECPM=2;
        $params->MIN_AD_EXCHANGE_AD_ECPM=1;
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        //$client=new \lib\storage\ES\ESClient(["192.168.56.102"]);
        //$res2=$client->query($t);
        $this->assertEquals("{\"index\":\"adx_floor_rules\",\"_source\":[\"AD_EXCHANGE_IMPRESSIONS\",\"AD_EXCHANGE_MATCHED_REQUESTS\",\"AD_EXCHANGE_AD_ECPM\",\"AD_EXCHANGE_PRICING_RULE_NAME\"],\"timeout\":\"30000ms\",\"size\":200,\"from\":100,\"body\":{\"query\":{\"bool\":{\"must\":{\"range\":{\"AD_EXCHANGE_AD_ECPM\":{\"gt\":1,\"lte\":2}}}}}}}",
            $res
        );
    }
}
