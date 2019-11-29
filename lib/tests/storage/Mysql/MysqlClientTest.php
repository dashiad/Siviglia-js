<?php
namespace lib\tests\storage\Mysql;
    $dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
    include_once($dirName);
    include_once(LIBPATH."/startup.php");
    include_once(LIBPATH."/autoloader.php");
    include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
    include_once(PROJECTPATH."/vendor/autoload.php");

    use PHPUnit\Framework\TestCase;
    use lib\model\BaseTypedObject;


class MysqlClientTest extends TestCase
{
    const TEST_TABLE="TestTable";
    const SAMPLE_QUERY="SELECT * from lineitemsummary where costType=\"CPC\"";

    var $client=null;
    function connect()
    {
        if($this->client==null) {
            $client = new \lib\storage\Mysql\Mysql(["host" => _DB_SERVER_, "user" => _DB_USER_, "password" => _DB_PASSWORD_]);
            $client->connect();
            $client->selectDb(_DB_NAME_);
            $this->client=$client;
        }
        return $this->client;
    }
    function createTestTable()
    {
        $client=$this->connect();
        if($client->tableExists(MysqlClientTest::TEST_TABLE))
            $client->deleteTable(MysqlClientTest::TEST_TABLE);
        $index = $client->doQ("CREATE TABLE ".MysqlClientTest::TEST_TABLE." (
            a_string VARCHAR(10),
            a_integer INT,
            a_byte CHAR(1),
            a_long LONG,
            a_float FLOAT,
            a_date DATE)");

    }
    function importSampleData()
    {
        $client=$this->connect();
        $client->importDump(__DIR__."/res/test.dmp");
    }

    function testCreateTable()
    {
        $this->createTestTable();
        $this->assertEquals(true, $this->client->tableExists(MysqlClientTest::TEST_TABLE));
        $this->client->deleteTable(MysqlClientTest::TEST_TABLE);
        $this->assertEquals(false, $this->client->tableExists(MysqlClientTest::TEST_TABLE));

    }
    function testSelect()
    {
        $this->importSampleData();
        $results=$this->client->select("SELECT count(*) as N from lineitemsummary where costType=\"CPC\"");
        $this->assertEquals(21,$results[0]["N"]);
        $results2=$this->client->select("SELECT * from lineitemsummary where costType=\"CPC\"","status");
        $this->assertEquals(1,count($results2["DELIVERING"]));
        $this->assertEquals(1,count($results2["COMPLETED"]));
    }
    function testFieldIndexedSelect()
    {
        $this->importSampleData();
        $n=0;
        $res=[];
        $indexedArr=[];
        $reindexBy="status";
        $t=$this->client->fieldIndexedSelect(
            $res,
            "SELECT * from lineitemsummary where costType=\"CPC\"",
            $n,
            "status",
            $indexedArr
        );
        // En el primer parametro, pasado a la funcion, se devuelve un diccionario
        // con una key por cada columna que hay en la tabla.
        $columns=array_keys($res);
        // EL resultado no indexado tiene como clave, cada una de las columnas.
        $this->assertEquals(57,count($columns));
        // Y como valor, un array con el valor de esa columna en cada linea.
        $this->assertEquals(21,count($res[$columns[0]]));

        // En el array indexado por el campo "status", tendremos, por cada valor
        // distinto de "status", las filas que contienen ese valor.
        $diffStatus=array_keys($indexedArr);
        $this->assertEquals(3,count($diffStatus));
        $this->assertEquals(19,count($indexedArr["DRAFT"]));
    }
    function testSelectColumn()
    {
        $this->importSampleData();
        $res=$this->client->selectColumn(MysqlClientTest::SAMPLE_QUERY." ORDER BY status","status");
        $this->assertEquals(21,count($res));
        $this->assertEquals("DELIVERING",$res[0]);
    }
    /* En selectIndexed, se indexa primero por el campo indicado, y  luego, por
    cada columna */
    function testSelectIndexed()
    {
        $this->importSampleData();
        $res=$this->client->selectIndexed(MysqlClientTest::SAMPLE_QUERY,"status");
        $this->assertEquals(true,isset($res["DRAFT"]));
        $this->assertEquals(true,isset($res["DRAFT"]["id"]));
        $this->assertEquals(19,count($res["DRAFT"]["id"]));
    }
    function testSelectAll()
    {
        $this->importSampleData();
        $n=0;
        $res=$this->client->selectAll(MysqlClientTest::SAMPLE_QUERY,$n);
        $this->assertEquals(21,$n);
        $this->assertEquals(21,count($res));
    }

    function testDoQ()
    {
        $this->importSampleData();
        $n=0;
        $res=$this->client->doQ("UPDATE lineitemsummary SET costType=\"OTHER\" where costType=\"CPC\"");
        $res=$this->client->selectAll(MysqlClientTest::SAMPLE_QUERY,$n);
        $this->assertEquals(0,$n);
        $this->assertEquals(0,count($res));
    }
    function testQuery()
    {
        $this->importSampleData();
        $n=0;
        $n=$this->client->query("UPDATE lineitemsummary SET costType=\"OTHER\" where costType=\"CPC\"");
        $this->assertEquals(21,$n);
    }
    function testCursor()
    {
        $this->importSampleData();
        $cr=$this->client->cursor(MysqlClientTest::SAMPLE_QUERY);
        $n=0;
        while($this->client->fetch($cr))
            $n++;
        $this->assertEquals(21,$n);
    }
    function testDelete()
    {
        $this->importSampleData();
        $n=0;
        $res=$this->client->delete("DELETE FROM lineitemsummary WHERE costType=\"CPC\"");
        $res=$this->client->selectAll(MysqlClientTest::SAMPLE_QUERY,$n);
        $this->assertEquals(0,$n);
        $this->assertEquals(0,count($res));
    }
    function testUpdateFromAssociative()
    {
        $this->importSampleData();
        $this->client->updateFromAssociative(
            "lineitemsummary",
            [
                "costType"=>"OTHER"
            ],
            "costType='CPC'"
        );
        $n=0;
        $res=$this->client->selectAll(MysqlClientTest::SAMPLE_QUERY,$n);
        $this->assertEquals(0,$n);
        $this->assertEquals(0,count($res));
    }

    function testInsertFromAssociative()
    {
        $this->importSampleData();
        $n=0;
        $res=$this->client->selectAll(MysqlClientTest::SAMPLE_QUERY,$n);
        $data=$res[0];
        $data["viewabilityProviderCompanyId"]=555;
        unset($data["id"]);
        $this->client->insertFromAssociative(
            "lineitemsummary",
            $data
        );
        $res=$this->client->selectAll("SELECT * from lineitemsummary where viewabilityProviderCompanyId=555",$n);

        $this->assertEquals(1,$n);
        $this->assertEquals(1,count($res));
    }
    function testGetTableSchema()
    {
        $this->importSampleData();
        $sch=$this->client->getTableSchema("lineitemsummary");
        $this->assertEquals(57,$sch["NCOLUMNS"]);
        $this->assertEquals(57,count(array_keys($sch["FIELDS"])));
    }
    function testSelectCallback()
    {
        $this->importSampleData();
        $n=0;
        $this->client->selectCallback(
            MysqlClientTest::SAMPLE_QUERY,
            function($arr) use(& $n)
            {
                $n++;
            }
        );
        $this->assertEquals(21,$n);
    }
    function testGetFullStatus()
    {
        $this->connect();
        $st=$this->client->getFullStatus();
        $this->assertEquals(true,isset($st["mysql"]));
        $this->assertEquals(true,isset($st["slave"]));
    }
}

