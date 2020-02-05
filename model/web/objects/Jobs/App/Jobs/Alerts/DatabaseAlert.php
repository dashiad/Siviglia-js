<?php
namespace model\web\Jobs\App\Jobs\Alerts;

use model\web\Jobs\App\Jobs\Messages\AlertMessage;
use \PDO;

class DatabaseAlert implements AlertInterface
{
    
    protected $message;
    protected $to = [];
    protected $database;
    
    public function __construct()
    {
        $host   = 'localhost';
        $dbname = 'jobs';
        $user   = 'jobs';
        $pass   = 'jobs';
        $this->database = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }
    
    public function setTo(Array $to)
    {
        foreach ($to as $address) {
            $this->to[] = $address;
        }
    }
    
    public function setMessage(AlertMessage $msg)
    {
        $this->message = json_encode($msg);
    }
    
    public function send() : Bool
    {
        foreach ($this->to as $address) {
            $sql  = "INSERT INTO alerts (message, to) VALUES(";
            $sql .= "'".$this->message."'";
            $sql .= "'".$address."')";
            echo $sql.PHP_EOL;
            $this->database->exec($sql);
            $this->database = null;
        }
        return true;
    }
    
    public static function replaceContent(String $text, Array $params)
    {
        foreach ($params as $key=>$value) {
            $text = str_replace($key, $value, $text);
        }
        return $text;
    }
}