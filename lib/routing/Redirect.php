<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 22/10/2017
 * Time: 3:25
 */

namespace lib\routing;


class Redirect
{
    var $url;
    function __construct($def,$params,$request)
    {

        $qsp = array();
        $url = $def["URL"];
        if($def["KEEP_QUERYSTRING"]) {
            // Se reconstruye la query string
            foreach ($_GET as $key2 => $value2) {
                if ($key2 == "request")
                    continue;
                $qsp[] = $key2 . "=" . urlencode($value2);
            }
            foreach ($params as $key2 => $value2)
                $qsp[] = $key2 . "=" . urlencode($value2);


            if (count($params) > 0) {
                if (strpos($url, "?") === false) {
                    $url .= "?";
                }
                $url .= "&" . implode("&", $qsp);
            }
        }
        $this->url=$url;

    }
    function resolve()
    {
        $response=Registry::$registry["response"];
        $response->addHeader("Location",$this->url);
    }
}