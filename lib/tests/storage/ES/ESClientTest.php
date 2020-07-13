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


class ESClientTest extends TestCase
{
    const TEST_INDEX_NAME="testIndex";
    function connect()
    {
        global $Config;
        $sc=$sc=$Config["SERIALIZERS"]["es"]["ES"];;
        $client=new \lib\storage\ES\ESClient($sc);
        return $client;
    }
    function createTestIndex($client)
    {
        if($client->indexExists(ESClientTest::TEST_INDEX_NAME)) {
            $client->destroyIndex(ESClientTest::TEST_INDEX_NAME);
        }
        $client=$this->connect();
        $index = $client->createIndex(ESClientTest::TEST_INDEX_NAME, ["_doc"=>["properties"=>[
            "a_string" => ["type" => "keyword"],
            "a_integer" => ["type" => "integer"],
            "a_byte" => ["type" => "byte"],
            "a_long" => ["type" => "long"],
            "a_float" => ["type" => "float"],
            "a_date" => ["type" => "date"]

        ]
    ]
        ]);
    }
    function resetTestIndex($client)
    {
        if($client->indexExists(ESClientTest::TEST_INDEX_NAME))
        {
            $client->destroyIndex(ESClientTest::TEST_INDEX_NAME);
            $this->assertEquals(false,$client->indexExists(ESClientTest::TEST_INDEX_NAME));
        }
        $this->createTestIndex($client);
    }

    function testCreateIndex()
    {
        $client=$this->connect();
            try{
            $this->createTestIndex($client);
            $exists=$client->indexExists(ESClientTest::TEST_INDEX_NAME);
            $this->assertEquals(true,$exists);
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch".$e->getMessage());
        }

    }
    function testDropIndex()
    {
        $client=$this->connect();
        try{
            if($client->indexExists(ESClientTest::TEST_INDEX_NAME))
            {
                $client->destroyIndex(ESClientTest::TEST_INDEX_NAME);
                $this->assertEquals(false,$client->indexExists(ESClientTest::TEST_INDEX_NAME));
            }
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch:".$e->getMessage());
        }
    }
    function testCreateIndexAlias()
    {
        $client=$this->connect();

        try{
            $this->createTestIndex($client);
            $client->createIndexAlias(ESClientTest::TEST_INDEX_NAME,ESClientTest::TEST_INDEX_NAME."_alias");
            $this->assertEquals(true,$client->indexExists(ESClientTest::TEST_INDEX_NAME."_alias"));
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch:".$e->getMessage());
        }
    }
    function testBulkInsert()
    {
        $client=$this->connect();
        try {
            $this->resetTestIndex($client);
            $sampleData=[
                [
                "a_string" => "hola","a_integer"=>2,"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ]
            ];
            $client->insertBulk($sampleData);
            sleep(1);
            $n=$client->getCount();
            $this->assertEquals(1,$n);
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch:".$e->getMessage());
        }
    }

    /**
     * Debe dar 1 error, que va a ser ignorado, y al final, el numero de lineas insertadas debe ser dos.
     */
    function testBulkInsert2()
    {
        $client=$this->connect();
        try {
            $this->resetTestIndex($client);
            $sampleData=[
                [
                    "a_string" => "hola","a_integer"=>2,"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ],
                [
                    "a_string" => "hola","a_integer"=>2,"a_byte"=>'c',"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ],
                [
                    "a_string" => "hola","a_integer"=>2,"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ]
            ];
            $client->insertBulk($sampleData,true);
            sleep(1);
            $n=$client->getCount();
            $this->assertEquals(3,$n);
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch:".$e->getMessage());
        }
    }
    function testQuery()
    {
        $client=$this->connect();
        try {
            $this->resetTestIndex($client);
            $n=$client->getCount();
            $sampleData=[
                [
                    "a_string" => "hola","a_integer"=>2,"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ],
                [
                    "a_string" => "adios","a_integer"=>2,"a_long"=>897987987,"a_float"=>3.2,"a_date"=>time()
                ]
            ];
            $client->insertBulk($sampleData,true);
            sleep(1);
            $res=$client->query([

                    'index' => ESClientTest::TEST_INDEX_NAME,
                    'body' => [
                        'query' => [
                            'match' => [
                                'a_float' => 3.2
                            ]
                        ]
                    ]
            ]);
            $this->assertEquals(2,$res["hits"]["total"]["value"]);
        }catch(\Exception $e)
        {
            $this->fail("Excepcion Elasticsearch:".$e->getMessage());
        }
    }
}

