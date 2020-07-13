<?php
namespace model\web\Jobs\App\Jobs\Workers;

use \PDO;

class MySqlWorker extends Worker
{
    protected $name = 'mysql_worker';
    
    protected function init()
    {
        //
    }
    
    public function runItem($item)
    {
        $host   = 'localhost';
        $dbname = 'jobs';
        $user   = 'jobs';
        $pass   = 'jobs';
        $table  = 'jobs';
        $items = $this->args['items'];
        foreach ($items as $index=>$item) {
            $date = new \DateTime($item);
            $items[$index] = "'".$date->format('Y-m-d')."'";
        }
        $items = implode(",", $items);
        
        $sql = "SELECT * FROM $table WHERE date IN ($items)";
        
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $result = $db->query($sql);
        $resultText = '';
        foreach ($result as $row) {
            $resultText .= $row['date'].": ".$row['value'].PHP_EOL;
        }
        $db = null;
        return $resultText;
    }
}