<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 19/04/2019
 * Time: 16:33
 */

namespace lib\data\Cursor\DFP;

use lib\data\Cursor\ReaderCursor;
use lib\data\Cursor\CompressedFileCursor;
use lib\data\Cursor\CSVArrayReaderCursor;

include_once(__DIR__."/../../../Google/DFP/AdManagerAPI.php");
include_once(__DIR__.'/../ReaderCursor.php');
include_once(__DIR__."/../CompressedFileCursor.php");
include_once(__DIR__.'/../CSVTransformCursor.php');
class DFPReportCursor extends ReaderCursor
{
    var $api;
    var $params;
    var $fileCursor;
    function init($params)
    {
        $iniFile=$params["iniFile"];
        $this->api=new \lib\Google\DFP\AdManagerAPI($iniFile);
        $this->params=$params;
        $this->fileCursor=new \lib\data\Cursor\CompressedFileCursor();
        $fileName=tempnam(sys_get_temp_dir(),"dfp_");
        $this->fileCursor->init(["fileName"=>$fileName]);
        $this->params["fileName"]=$fileName;

    }
    function produce()
    {
        $this->api->report($this->params);
        $this->fileCursor->process();
    }
    function addCursor($c)
    {
        $this->fileCursor->addCursor($c);
    }
}
