<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 23/12/2017
 * Time: 22:36
 */

namespace lib\output\commandLine;


use Startup;

class CLRequest extends \lib\output\html\HTMLRequest
{

    private $userId=null;
    function __construct()
    {
        $params=$this->parseParameters();

        $this->site=isset($params["site"])?$params["site"]:"default";
        $this->isSSL=false;
        if(!isset($params["subpath"]))
            $subpath="/";
        else
        {
            $subpath=$params["subpath"];
            if($subpath[0]!="/")
                $subpath="/".$subpath;
        }

        $this->requestedPath=$subpath;
        $this->outputType="html";
        if(isset($params["output"]))
        {
            switch($params["output"])
            {
                case 'xlsx':
                {
                    $this->outputType='xlsx';
                }break;
                case 'csv':
                {
                    $this->outputType='csv';
                }break;
                case 'pdf':
                {
                    $this->outputType='pdf';
                }break;
                default:
                {
                    $this->outputType="json";
                }
            }

            unset($params["output"]);
        }

        $this->parameters=$params;
        if(isset($params["POST"]))
        {

            $decoded=json_decode($params["POST"]);
            if(!$decoded)
            {
                die("POST parameter cannot be decoded");
            }
            $this->actionData=$decoded;
            unset($params["POST"]);
        }
        if(isset($params["userId"]))
        {
            $this->userId=intval($params["userId"]);
        }
        $session=new \lib\storage\Persistence\NullSession();
        \Registry::$registry[\Registry::SERVICE_CONTAINER]->addService("session",$session);
        \Registry::initialize($this);
    }

    function parseParameters($noopt = array()) {
        $result = array();
        $params = $GLOBALS['argv'];
        // could use getopt() here (since PHP 5.3.0), but it doesn't work relyingly
        reset($params);
        while (list($tmp, $p) = each($params)) {
            if ($p{0} == '-') {
                $pname = substr($p, 1);
                $value = true;
                if ($pname{0} == '-') {
                    // long-opt (--<param>)
                    $pname = substr($pname, 1);
                    if (strpos($p, '=') !== false) {
                        // value specified inline (--<param>=<value>)
                        list($pname, $value) = explode('=', substr($p, 2), 2);
                    }
                }
                // check if next parameter is a descriptor or a value
                $nextparm = current($params);
                if (!in_array($pname, $noopt) && $value === true && $nextparm !== false && $nextparm{0} != '-') list($tmp, $value) = each($params);
                $result[$pname] = $value;
            } else {
                // param doesn't belong to any option
                $result[] = $p;
            }
        }
        return $result;
    }


    function getClientData()
    {
        return null;
    }

    function startPersistence()
    {
        return;
    }

    function getUser()
    {
        global $oCurrentUser;
        if(isset($oCurrentUser))
            return $oCurrentUser;
        $s=\Registry::getService("model");
        $oCurrentUser=$s->getModel("\\model\\web\\WebUser");

        if ($this->userId)
        {
            $oCurrentUser->setLogged($this->userId);
        }

        return $oCurrentUser;
    }


    // Returns the array of accepted languages, sorted by priority.
    function getLanguages()
    {
        return array(DEFAULT_LANGUAGE);
    }

    function getCurrentDomain()
    {
        $site=\Registry::getService("site");
        return $site->getCanonicalUrl();
    }
    function solve()
    {

    //    Startup::commonSetup();
    }
}
