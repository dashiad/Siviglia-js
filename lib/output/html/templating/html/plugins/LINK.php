<?php

include_once(__DIR__."/../../Plugin.php");
class Link extends Plugin
{
    function __construct($parentWidget,$layoutContents,$layoutManager)
    {

        $this->parentWidget=$parentWidget;
        $this->layoutContents=$layoutContents;
        $this->layoutManager=$layoutManager;
    }

    function parse(){
        $parsed=$this->parseNode($this->layoutContents,true);
        $spec=array();
        for($k=0;$k<count($parsed);$k++)
        {
            $spec[$parsed[$k][0]]=$parsed[$k][1];
        }
        $page=trim($spec["PAGE"]);
        $params=$spec["PARAMS"];
        $parts=explode(",",$params);
        $pairs=array();
        $router=Registry::getService("router");
        $phpVars=0;
        $pText="array(";
        for($k=0;$k<count($parts);$k++)
        {
            $p1=explode("=",$parts[$k]);
            if(count($p1)==2)
            {
                $pText.=($k>0?",":"")."'".$p1[0]."'=>";
                if($p1[1][0]=='$') {
                    $phpVars = 1;
                    $pText.=$p1[1];
                }
                else
                {
                    $pText.="'".addslashes($p1[1])."'";
                }
                $pairs[trim($p1[0])]=trim($p1[1]);
            }
        }
        $pText.=")";
        if($phpVars==0) // No es un link dinamico, lo podemos resolver ya.
        {
            $url=$router->generateUrl($page,$pairs);
            return array(array("TYPE"=>"HTML","TEXT"=>$url));
        }


        return array(array("TYPE"=>"PHP","TEXT"=>"<?"."php \$f=1;echo Registry::getService(\"router\")->generateUrl(\"$page\",$pText);?>"));

    }

}