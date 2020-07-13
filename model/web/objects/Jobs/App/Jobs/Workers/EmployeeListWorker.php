<?php
namespace model\web\Jobs\App\Jobs\Workers;

use \PDO;

class EmployeeListWorker extends Worker
{

    protected $name = 'employee_list_worker';
    
    protected function init()
    {
        //
    }

    protected function runItem($item)
    {
        $host   = 'localhost';
        $dbname = 'employees';
        $user   = 'jobs';
        $pass   = 'jobs';

        $sql = "SELECT * FROM employees E JOIN titles T on E.emp_no=T.emp_no GROUP BY T.emp_no ORDER BY hire_date DESC";
        
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $result = $db->query($sql);
	$resultText = '';
	if ($result)
            foreach ($result as $row) {
                $resultText .= $row['last_name'].", ".$row['first_name']."->".$row['title'].PHP_EOL;
            }
        $db = null;
        return $resultText;
    }
}

