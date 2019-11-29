<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 17:36
 */

namespace lib\data\Cursor\DFP;

namespace lib\data\Cursor\DFP;


use lib\data\Cursor\ReaderCursor;
use lib\data\Cursor\CompressedFileCursor;
use lib\data\Cursor\CSVArrayReaderCursor;

include_once(__DIR__."/../../../Google/DFP/AdManagerAPI.php");
include_once(__DIR__.'/../ReaderCursor.php');

class DFPServiceCursor extends ReaderCursor
{
    var $api;
    var $params;
    function init($params)
    {
        $iniFile=$params["iniFile"];
        $this->api=new \lib\Google\DFP\AdManagerAPI($iniFile);
        $this->params=$params;
    }
    function produce()
    {
        $service=$this->api->getService($this->params["serviceName"]);
        $me=$this;
        $service->query($this->params["base"],array(),$this->params["serviceNamePlural"],
            function($row) use ($me){
                $me->push($row);
            });
        return false;
    }
}


