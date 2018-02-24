<?php

namespace lib\output\html;
include_once(LIBPATH."/Request.php");


class HTMLRequest extends \Request implements \ArrayAccess
{
    var $parameters;
    var $actionData;
    var $filesData;
    var $client;
    var $requestedPath;
    var $urlCandidate;
    var $site;
    var $isSSL=false;
    var $outputType;
    function __construct()
    {
        $this->site=isset($_GET["site"])?$_GET["site"]:"default";
        if(!empty($_SERVER["HTTPS"]))
            $this->isSSL=true;

        if(!isset($_GET["subpath"]))
            $subpath="/";
        else
        {
            $subpath=$_GET["subpath"];
            if($subpath[0]!="/")
                $subpath="/".$subpath;

            if(strpos($subpath,"/scripts/dojo/")===0)
            {
                $subpath=trim(str_replace("//","/",$subpath),"/");
                $parts=explode("/",$subpath);
                switch($parts[5])
                {
                    case "actions":
                    {
                        $nParts=count($parts)-1;
                        $lastPart=$parts[$nParts];
                        $p2=explode(".",$lastPart);
                        if($p2[1]=='js')

                            include(\lib\Paths::getDojoActionJs($parts[2],$parts[3],$p2[0]));
                        else
                        {
                            include(\lib\Paths::getDojoActionTemplate($parts[2],$parts[3],$p2[0]));
                        }
                    }break;
                }
                exit();
            }
        }

        $this->requestedPath=$subpath;
        unset($_GET["subpath"]);
        unset($_GET["site"]);
        $this->outputType="html";

        if(isset($_GET["output"]))
        {
            switch($_GET["output"])
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

            unset($_GET["output"]);
        }

        $this->parameters=$_GET;
        $this->actionData=$_POST;
        $this->filesData=$_FILES;
        $_GET=null;
        $_POST=null;
        $_FILES=null;
        $session=new \lib\storage\Persistence\PHPSession();
        \Registry::addService("session",$session);

    }
    function getCurrentSite()
    {
        return $this->site;
    }
    function getRawGet()
    {
        return $this->parameters;
    }
    function getRawPost()
    {
        return $this->actionData;
    }
    function getOutputType()
    {
        return $this->outputType;
    }
    function getRequestedPath()
    {
        return $this->requestedPath;
    }
    function getClientData()
    {
        if($this->client) {
            return $this->client;
        }

        /**
         * NOTA: por lentitud en la petición se ha decidido utilizar una alternativa a get_browser, si fuera necesario
         * volver a la versión anterior, hay que descomentar este código y comentar el que sigue.
         * Es recomendable actualizar periódicamente el fichero de definiciones de browsers.
         *
        $browser=array();
        if(!defined('HHVM_VERSION')) {
        //$browser = @get_browser(null, true);
        }
         **/

        require_once(LIBPATH.'/php/phpbrowscap/Browscap.php');
        $bc = new \phpbrowscap\Browscap(LIBPATH.'/php/phpbrowscap/cache');
        $bc->localFile = LIBPATH.'/php/phpbrowscap/lite_php_browscap.ini';
        $browser = $bc->getBrowser();

        $this->client=array(
            "request" => $_SERVER["QUERY_STRING"],
            "referer" => io($_SERVER,"HTTP_REFERER",""),
            "browser" => $browser?$browser->Browser:'',
            "platform" => $browser->Platform,
            "version" => $browser->Version,
            "device_type" => $browser->Device_Type,
            "isMobileDevice" => $browser->isMobileDevice,
            "isTablet" => $browser->isTablet,
            "ip" => \lib\model\types\IP::getCurrentIp()
        );

        return $this->client;
    }

    function getActionData()
    {
        if(!isset($this->actionData))
            return null;

        if(!isset($this->actionData["FORM"]))
            return null;

        $action=array(
            "name" => $this->actionData["FORM"],
            "object" => $this->actionData["MODEL"],
            "INPUTS" => $this->actionData["INPUTS"],
            "FIELDS" => $this->actionData["FIELDS"],
            "site"=> $this->actionData["SITE"],
            "page"=>$this->actionData["PAGE"],
            "sourcepage"=>isset($this->actionData["SOURCEPAGE"])?$this->actionData["SOURCEPAGE"]:null,
            "validationCode" => isset($this->actionData["__FROM"])?$this->actionData["__FROM"]["SECCODE"]:null,
            "keys" => isset($this->actionData["KEYS"])?$this->actionData["KEYS"]:null
        );
        if(isset($this->actionData["KEYS"]))
        {
            foreach($this->actionData["KEYS"] as $key=>$value)
                $action["FIELDS"][$key]=$value;
        }

        $fields=& $action["FIELDS"];

        // Se unen los datos recibidos por FILES, con los recibidos por post
        if (!empty($this->filesData))
        {
            // if (!is_array(\Registry::$registry["action"]))
            //    \Registry::$registry["action"] = array();
            $keys = array_keys($this->filesData);
            $nKeys = count($keys);
            for ($k = 0; $k < $nKeys; $k++)
            {
                if (is_array($this->filesData[$keys[$k]]["name"]))
                {
                    $subKeys = array_keys($this->filesData[$keys[$k]]);
                    $curItem = & $this->filesData[$keys[$k]];
                    $nameKeys = array_keys($this->filesData[$keys[$k]]["name"]);
                    $nFiles = count($nameKeys);
                    for ($j = 0; $j < $nFiles; $j++)
                    {
                        $curName = $nameKeys[$j];
                        for ($h = 0; $h < count($subKeys); $h++)
                        {
                            $fields[$keys[$k]][$nameKeys[$j]][$subKeys[$h]] = $curItem[$subKeys[$h]][$curName];
                        }
                    }
                }
                else
                    $fields[$keys[$k]]=$this->filesData[$keys[$k]];
            }
        }

        return $action;
    }


    function getUser()
    {
        return \Registry::getService("user");
    }

    function getQueryString()
    {
        $qS="";
        $c=0;
        foreach($this->parameters as $key=>$value)
        {
            if( $key!="subpath" )
                $qS.=($c++>0?"&":"").$key."=".$value;
        }
        return $qS;
    }

    // Returns the array of accepted languages, sorted by priority.
    function getLanguages()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }
                // sort list based on value
                arsort($langs, SORT_NUMERIC);
                return $langs;
            }
        }
        return array();
    }

    function setResolvedUrl($urlSpec,$parameters)
    {
        $this->urlCandidate=$urlSpec;
        $this->parameters["REQUEST_PATH"]=$urlSpec;
        if( $parameters )
        {
            foreach($parameters as $key=>$value)
                $this->parameters[$key]=$value;
        }
        /*
            WARNING: $_GET gets OVERWRITTEN by the parameters specified in the url mapping file.
        */
        $this["LAYOUT"]=$this->parameters["REQUEST_PATH"];
    }

    function getCurrentUrlName()
    {
        return $this->urlCandidate["NAME"];
    }

    function getLink($overridenParams,$ignoreParams)
    {
        $params=array_merge($this->parameters,$overridenParams);
        foreach($ignoreParams as $value)
            unset($params[$value]);
        $link= Router::buildLink($this->urlCandidate["NAME"],$params);
        return $link;
    }

    function getTranslatedParamName($param)
    {
        if( !isset($this->parameters["REQUEST_PATH"]) )
            return "";
        if( !isset($this->parameters["REQUEST_PATH"]["PARAMS"][$param]) )
            return "";
        if( isset($this->parameters["REQUEST_PATH"]["PARAMS"][$param]["NAME"] ))
            return $this->parameters["REQUEST_PATH"]["PARAMS"][$param]["NAME"];
        return $param;
    }

    function getCandidate()
    {
        return $this->urlCandidate;
    }




    function offsetExists($idx)
    {
        return array_key_exists($idx,$this->parameters);
    }
    function offsetGet($idx)
    {
        if( $this->offsetExists($idx) )return $this->parameters[$idx];
        return null;
    }
    function offsetSet($idx,$val)
    {
        $this->parameters[$idx]=$val;
    }
    function offsetUnset($idx)
    {
        unset($this->parameters[$idx]);
    }

    function getCurrentDomain()
    {
        return $_SERVER["HTTP_HOST"];
    }

    static function parseUrl($url) {

        $r  = "(?:(?P<scheme>[a-z0-9+-._]+)://)?";
        $r .= "(?:";
        $r .=   "(?:(?P<credentials>(?:[a-z0-9-._~!$&'()*+,;=:]|%[0-9a-f]{2})*)@)?";
        $r .=   "(?:\[((?:[a-z0-9:])*)\])?";
        $ip="(?:[0-9]{1,3}+\.){3}+[0-9]{1,3}";//ip check
        $s="(?P<subdomain>[-\w\.]+)\.)?";//subdomain
        $d="(?P<domain>[-\w]+\.)";//domain
        $e="(?P<extension>\w+)";//extension

        $r.="(?P<host>(?(?=".$ip.")(?P<ip>".$ip.")|(?:".$s.$d.$e."))";
        $r .=   "(?::(?P<port>\d*))?";
        $r .=   "(?P<path>/(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9a-f]{2})*)?";
        $r .=   "|";
        $r .=   "(/?";
        $r .=     "(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9a-f]{2})+";
        $r .=     "(?:[a-z0-9-._~!$&'()*+,;=:@\/]|%[0-9a-f]{2})*";
        $r .=    ")?";
        $r .= ")";
        $r .= "(?:\?(?P<query_string>(?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9a-f]{2})*))?";
        $r .= "(?:#(?P<fragment>(?:[a-z0-9-._~!$&'()*+,;=:\/?@]|%[0-9a-f]{2})*))?";
        $matches=preg_match("`$r`i", $url, $match);
        if( !$matches )return false;
        for( $k=0;$k<14;$k++ )
            unset($match[$k]);
        return $match;
    }
    function getParameters()
    {
        return $this->parameters;
    }
    function getUrl()
    {
        $queryString=$this->getQueryString();
        return $this->requestedPath.($queryString?"?".$queryString:"");

    }
    function getUnSerializerType()
    {
        return "HTML";
    }
}
