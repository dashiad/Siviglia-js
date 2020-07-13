<?php
namespace lib\storage\Oauth;

class OauthException extends \lib\model\BaseException
{
    const ERR_NO_CONNECTION = 1;
}

class Oauth
{
    var $conn;
    static $nInstances=0;
    static $nUpdates=0;
    static $lastWhere='';
    // Niveles de debug : 0 : No debug
    //                    1 : Mostrado de errores.
    //                    2 : Mostrado de todas las queries.
    var $debugLevel=0;
    var $currentDb=NULL;
    var $host;
    
    public function __construct($definition)
    {
        $this->myInstance = static::$nInstances;
        static::$nInstances++;
        
        $this->definition = $definition;
    }
    
    public function connect()
    {
        // $this->conn = ?
    }
    
    public function getConnection()
    {
        return $this->conn;
    }
    
    public function request($q, $field="")
    {
        $results = [];
        return $results;
    }
    
}
