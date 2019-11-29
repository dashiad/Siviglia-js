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
use lib\storage\ES\QueryBuilder;


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
                    'TRIGGER_VAR'=>'Unified_pricing_rule'
                )
            )
        );

        $params->Unified_pricing_rule="SC_KIBANA_FFLR_2.75EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);

        $this->assertEquals("{\"index\":\"SampleData\",\"_source\":[\"Date\",\"Ad_Unit\",\"Ad_Exchange_impressions\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must\":[{\"term\":{\"Unified_pricing_rule\":\"SC_KIBANA_FFLR_2.75EUR\"}}]}}}}",
            $res
        );

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
                    'TRIGGER_VAR'=>'Unified_pricing_rule'
                )
            )
        );

        $params->Unified_pricing_rule="SC_KIBANA_FFLR_2.75EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);

        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);
        $this->assertEquals($data["hits"]["total"]["value"],190);

        $this->assertEquals("{\"index\":\"SampleData\",\"_source\":[\"Date\",\"Ad_Unit\",\"Ad_Exchange_impressions\"],\"timeout\":\"30000ms\",\"body\":{\"query\":{\"bool\":{\"must_not\":[{\"term\":{\"Unified_pricing_rule\":\"SC_KIBANA_FFLR_2.75EUR\"}}]}}}}",
            $res
        );



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

        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Date"],"timeout":"30000ms","body":{"query":{"bool":{"must":[{"range":{"Total_impressions":{"gt":100}}}]}}}}',
            $res
        );
    }
    function testRangedDoubleCondition()
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
                    "MAX_IMPRESSIONS"=>array("TYPE"=>"Decimal"),
                    "MIN_REVENUE"=>array("TYPE"=>"DECIMAL"),
                    "MAX_REVENUE"=>array("TYPE"=>"DECIMAL")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                'MIN_IMPRESSIONS'=>array("TYPE"=>"Decimal","REQUIRED"=>true),
                'MAX_IMPRESSIONS'=>array("TYPE"=>"Decimal","REQUIRED"=>false),
                "MIN_REVENUE"=>array("TYPE"=>"DECIMAL","REQUIRED"=>false),
                "MAX_REVENUE"=>array("TYPE"=>"DECIMAL","REQUIRED"=>false)
            ),
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
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
                        'F'=>'Ad_Exchange_revenue',
                        'OP'=>'>',
                        'V'=>'{%MIN_REVENUE%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_REVENUE'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Ad_Exchange_revenue',
                        'OP'=>'<=',
                        'V'=>'{%MAX_REVENUE%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_REVENUE'
                )
            )
        );

        $params->MIN_IMPRESSIONS=10000;
        $params->MIN_REVENUE=2;
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);

        $conn=$this->getDefaultSerializer()->getConnection();
        $data=$conn->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","body":{"query":{"bool":{"must":[{"range":{"Total_impressions":{"gt":10000}}},{"range":{"Ad_Exchange_revenue":{"gt":2}}}]}}}}',
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
                    'Unified_pricing_rule'=>array("TYPE"=>"String","REQUIRED"=>true)
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
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
                        'F'=>'Unified_pricing_rule',
                        'OP'=>'!=',
                        'V'=>'{%Unified_pricing_rule%}'
                    ),
                    'TRIGGER_VAR'=>'Unified_pricing_rule'
                )
            )
        );

        $params->MIN_IMPRESSIONS=20000;
       // $params->MIN_AD_EXCHANGE_AD_ECPM=1;
        $params->Unified_pricing_rule="SC_KIBANA_FFLR_0.50EUR";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","body":{"query":{"bool":{"must":[{"range":{"Total_impressions":{"gt":20000}}}],"must_not":[{"term":{"Unified_pricing_rule":"SC_KIBANA_FFLR_0.50EUR"}}]}}}}',
            $res);
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
                    'Unified_pricing_rule'=>array("TYPE"=>"String","REQUIRED"=>true),
                    'Ad_unit'=>array("TYPE"=>"STRING")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
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
                        'F'=>'Unified_pricing_rule',
                        'OP'=>'=',
                        'V'=>'{%Unified_pricing_rule%}'
                    ),
                    'TRIGGER_VAR'=>'Unified_pricing_rule'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Ad_unit',
                        'OP'=>'=',
                        'V'=>'{%Ad_unit%}'
                    ),
                    'TRIGGER_VAR'=>'Ad_unit'
                )
            )
        );


        $params->Unified_pricing_rule="SC_KIBANA_FFLR_0.50EUR";
        $params->Ad_unit="HOGARUTIL.ES#ES000085";
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","body":{"query":{"bool":{"must":[{"term":{"Unified_pricing_rule":"SC_KIBANA_FFLR_0.50EUR"}},{"term":{"Ad_unit":"HOGARUTIL.ES#ES000085"}}]}}}}',
            $res
        );
    }

    function testAggregation()
    {
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                'type'=>array("TYPE"=>"Decimal","REQUIRED"=>true)
            ),
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
            'GROUPBY'=>"Unified_pricing_rule => (1000)Total_impressions => (10)Ad_Exchange_revenue"

        );
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,null);
        $t=$qb->build();
        $res=json_encode($t);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","body":{"aggs":{"Unified_pricing_rule":{"terms":{"field":"Unified_pricing_rule","size":1000},"aggs":{"Total_impressions":{"histogram":{"field":"Total_impressions","interval":"1000"},"aggs":{"Ad_Exchange_revenue":{"histogram":{"field":"Ad_Exchange_revenue","interval":"10"}}}}}}}}}',
            $res
        );
    }

    /**
     *  Test de parametros de paginacion
     */
    function testPaging()
    {
        /*
         * "Date"=,"Ad_unit","Unified_pricing_rule","Ad_unit_ID","Unified_pricing_rule_ID",
            "Total_impressions","Total_CTR","Ad_Exchange_impressions"=>["type"=>"long"],
            "Ad_Exchange_revenue"=>["type"=>"float"]
         */

        $params=new BaseTypedObject(

            array(
                "FIELDS"=>array(
                    "MIN_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>            array(
                "FIELDS"=>array(
                    "MIN_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_AD_ECPM"=>array("TYPE"=>"Decimal")
                )
            ),
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
            "LIMIT"=>100
        );

        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","size":100,"body":[]}',
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
                    "MIN_AD_EXCHANGE_REVENUE"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_REVENUE"=>array("TYPE"=>"Decimal")
                )
            )
        );
        $def=array(
            "INDEX"=>QueryBuilderTest::TEST_INDEX_NAME,
            'PARAMS'=>array(
                "FIELDS"=>array(
                    "MIN_AD_EXCHANGE_REVENUE"=>array("TYPE"=>"Decimal"),
                    "MAX_AD_EXCHANGE_REVENUE"=>array("TYPE"=>"Decimal")
                )
            ),
            'BASE'=>['Total_impressions','Unified_pricing_rule','Ad_Exchange_revenue'],
            'CONDITIONS'=>array(
                array(
                    'FILTER'=>array(
                        'F'=>'Ad_Exchange_revenue',
                        'OP'=>'>',
                        'V'=>'{%MIN_AD_EXCHANGE_REVENUE%}'
                    ),
                    'TRIGGER_VAR'=>'MIN_AD_EXCHANGE_REVENUE'
                ),
                array(
                    'FILTER'=>array(
                        'F'=>'Ad_Exchange_revenue',
                        'OP'=>'<=',
                        'V'=>'{%MAX_AD_EXCHANGE_REVENUE%}'
                    ),
                    'TRIGGER_VAR'=>'MAX_AD_EXCHANGE_REVENUE'
                )
            ),
            "STARTINGROW"=>100,
            "PAGESIZE"=>200
        );

        $params->MAX_AD_EXCHANGE_REVENUE=2000;
        $params->MIN_AD_EXCHANGE_REVENUE=1;
        $serializer=$this->getDefaultSerializer();
        $qb=new QueryBuilder($serializer,$def,$params);
        $t=$qb->build();
        $res=json_encode($t);
        $ser=$this->getDefaultSerializer();
        $client=$ser->getConnection();
        $res2=$client->query($t);
        $this->assertEquals('{"index":"SampleData","_source":["Total_impressions","Unified_pricing_rule","Ad_Exchange_revenue"],"timeout":"30000ms","size":200,"from":100,"body":{"query":{"bool":{"must":[{"range":{"Ad_Exchange_revenue":{"gt":1,"lte":2000}}}]}}}}',
            $res
        );
    }
}
