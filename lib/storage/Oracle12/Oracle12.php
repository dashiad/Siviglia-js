<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 02/09/2016
 * Time: 11:10
 */

namespace lib\storage\Oracle12;
include_once(PROJECTPATH."/vendor/autoload.php");

class Oracle12
{
    static $connection=null;
    var $username;
    var $password;
    var $connString;
    function __construct($username,$password,$connString)
    {
        $this->username=$username;
        $this->password=$password;
        $this->connString=$connString;
    }
    function getConnection()
    {
        if(Oracle12::$connection!=null)
            return Oracle12::$connection;
	    echo "Connecting to ".$this->connString."\n";
        Oracle12::$connection=oci_connect($this->username,$this->password,$this->connString);
        $stid=oci_parse(Oracle12::$connection,"alter session set nls_date_format = 'YYYY-MM-DD HH24:MI:SS'");
        oci_execute($stid);
        return Oracle12::$connection;
    }
    function removeBetweenDates($table,$dateField,$start,$end)
    {
        $conn=$this->getConnection();
        $parsedTable=$this->parseTable($table);
        $q="DELETE FROM ".$parsedTable." WHERE $dateField >= '".$start." 00:00' AND ".$dateField." <='".$end." 00:00'";
        $stid = oci_parse($conn, $q);
        oci_execute($stid);
        oci_commit($conn);
    }
    function parseTable($table)
    {
        $parts=explode(".",$table);
        return "\"".$parts[0]."\"".(count($parts)>0?".\"".$parts[1]."\"":"");
    }
    static $counter=0;
    function insertBulk($table,$lines,$definition)
    {
        $columnMap=isset($definition["oracle"]["columnMap"])?array_flip($definition["oracle"]["columnMap"]):"";

        $parsedTable=$this->parseTable($table);
        $client=$this->getConnection();
        $v=array_keys($lines[0]);
        for($k=0;$k<count($v);$k++)
        {
            if(isset($columnMap[$v[$k]]))
                $v[$k]=$columnMap[$v[$k]];
        }

        $q="INSERT INTO  $parsedTable (\"".implode("\",\"",$v)."\")";
        for($k=0;$k<count($lines);$k++)
        {
            if($k>0)
                $q.=" union all ";
            $q.=" select '".implode("','",$lines[$k])."' from dual";
        }
        if(Oracle12::$counter==0) {
            Oracle12::$counter=1;
            echo $q;
        }
        $stid=oci_parse($client,$q);
        oci_execute($stid);
        oci_commit($client);
	    echo "ORA::Inserting Bulk\n";
    }
}
