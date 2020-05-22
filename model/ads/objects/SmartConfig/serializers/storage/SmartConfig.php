<?php
namespace model\ads\SmartConfig\serializers\SmartConfig\storage;

require_once(__DIR__.'/QueryBuilder.php');

use \lib\php\ParametrizableString;
// use \GuzzleHttp\Client;
// use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Exception\ConnectException;

class SmartConfigException extends \lib\model\BaseException
{
     const ERR_INVALID_METHOD     = 1;
}


class SmartConfig
{
    public function __construct(?Array $definition)
    {
        $this->definition = $definition;
    }
    
    public function request($q="", $field="")
    {
        echo "hi there, I'm an SmartConfig request";
    }
}

