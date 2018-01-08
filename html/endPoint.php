<?php
include_once("localConfig.php");
include_once(CUSTOMLIB."../config/config.inc.php");
define(PROJECTPATH,CUSTOMLIB."../");
$obj=$_GET["o"];
$mysql=new MySQL("192.168.253.1", _LOCAL_DB_USER_, _LOCAL_DB_PASSWORD_, "datamine");
ini_set("memory_limit","512M");
if(isset($_GET["ds"]))
{
    $cName=$_GET["ds"];
    $fcName='\json\datasources\\'.$cName;
    include_once(PROJECTPATH."/backoffice/output/json/datasources/".$_GET["ds"].".php");
    $nDs=new $fcName();
    $nDs->fetch();
}
if(isset($_GET["action"]))
{
    $cName=$_GET["action"];
    $acName='\json\actions\\'.$cName;
    include_once(CUSTOMLIB."../backoffice/output/json/actions/".$_GET["action"].".php");
    $nAc=new $acName($_POST);
    $result=$nAc->execute();
}