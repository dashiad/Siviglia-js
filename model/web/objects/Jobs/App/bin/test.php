<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\JobManager;
use model\web\Jobs\App\Queue;

/*$queue = Jobs\Queue::create('test');
$args = array (
    'type' => 'job',
    'name' => 'simplest_job',
    'task' =>
    array (
        'type' => 'task',
        'name' => 'data_mixer',
        'args' =>
        array (
            'task' => 'Test',
            'type' => 'DateRange',
            'params' =>
            array (
                'start_date' => '2019-11-01 00:00:00',
                'end_date' => '2019-11-30 23:59:59',
                'max_chunk_size' => 30,
            ),
        ),
    ),
);
$job = new Jobs\Runnables\Job($queue, $args);
$job->start();
//Jobs\Persist::save($job);
//$job->persist();
 */

/*
 * 
 * 
 * 
 
  function testCreateJobsTable()
    {
        $model = new Job;
        $res = \Registry::getService("storage")->getSerializerByName('web');
        $res->createStorage($model, ["test"=>"test"], 'Job');
    }
    
    function testCreateWorkersTable()
    {
        $model = new Worker;
        $res = \Registry::getService("storage")->getSerializerByName('web');
        $res->createStorage($model, ["test"=>"test"], 'Worker');
    }
    
    function testCreateJobs()
    {
        $job = new Job;
        $job->job_id = uniqid('test_job_');
        $job->name = 'test_job';
        $job->object = '<objeto_serializado>';
        $job->save();
        
        for ($i=0;$i<2;$i++) {
            $child = new Job();
            $child->job_id = uniqid('child_job_');
            $child->parent = $job->job_id;
            $child->name = 'child_job';
            $child->object = '<objeto_serializado>';
            $child->save();
            for($j=0;$j<4;$j++) {
                $worker = new Worker();
                $worker->name = "TestWorker";
                $worker->job_id = $child->job_id;
                $worker->worker_id = uniqid($worker->job_id.'.'.$worker->name.'_');
                $worker->index = $j;
                $worker->number_of_parts=4;
                $worker->status = 1;
                $worker->items = json_encode([1,2,3,4]);
                $worker->object = '<objeto_serializado>';
                $worker->save();
            }
        }
    }
 
  function testFindJobs()
    {
        $job = new Job;
        $job->id_job = 3;
        $job->loadFromFields();
        $this->assertEquals("waiting", \model\web\Job::getStatus($job->status));
    }
    
    function testListJobs()
    {
        $job = new Job;
        $job->id_job=3;
        $job->loadFromFields();
        $job->workers->getRelationValues();
        $this->assertEquals(4, $job->workers->count());
    }
    function testInvokeDatasource()
    {
        $ds=\lib\datasource\DataSourceFactory::getDataSource("\model\web\Job", "FullList");
        $ds->status=Job::WAITING;
        $it = $ds->fetchAll();
        $data = $it->getFullData();
        foreach($data as $job) {
            echo $job->job_id;
        }
        this->assertEquals(3, $it->count());
    }
    */
function testCreateJobsTable()
{
    $model = new \model\web\Jobs;
    $res = \Registry::getService("storage")->getSerializerByName('web');
    $res->createStorage($model, ["test"=>"test"], 'Job');
}

function testCreateWorkersTable()
{
    $model = new \model\web\Jobs\Worker;
    $res = \Registry::getService("storage")->getSerializerByName('web');
    $res->createStorage($model, ["test"=>"test"], 'Worker');
}

function testCreateSimpleJob()
{
    $args = array (
        'type' => 'job',
        'name' => 'simplest_job',
        'max_retries' => 1,
        'task' =>
        array (
            'type' => 'task',
            'name' => 'data_mixer',
            'args' =>
            array (
                'task' => 'Test',
                'type' => 'DateRange',
                'params' =>
                array (
                    'start_date' => '2019-11-01 00:00:00',
                    'end_date' => '2019-11-30 23:59:59',
                    'max_chunk_size' => 10,
                ),
            ),
        ),
    );
    JobManager::createJob($args);
}

function testCreateMySqlJob()
{
    $args = array (
        'type' => 'job',
        'name' => 'daily_report',
        'task' =>
        array (
            'type' => 'task',
            'name' => 'data_mixer',
            'max_running_children' => 2,
            'args' =>
                array (
                    'task' => 'MySql',
                    'type' => 'DateRange',
                    'params' =>
                    array (
                        'start_date' => '2019-11-01 00:00:00',
                        'end_date' => '2019-11-30 23:59:59',
                        'max_chunk_size' => 30,
                    ),
                ),
        ),
    );
    JobManager::createJob($args);
}

function testCreateEmployeeReport() 
{
    $args = array (
        'type' => 'job',
        'name' => 'employee_report',
        'task' =>
        array (
            'type' => 'task',
            'name' => 'employee_sql_query',
            'max_running_children' => 2,
            'args' =>
            array (
                'task' => 'EmployeeList',
                'type' => 'None',
                'params' => array()
            ),
        ),
    );
    JobManager::createJob($args);
}

function testCreateTrigger()
{
    $args = [
        "type" =>  "trigger",
        "name" =>  "alerts_on_daily_report",
        "on" =>  "daily_report",
        "run_on_partials" =>  true,
        "job" =>  [
            "type" =>  "job",
            "name" =>  "daily_alert",
            "max_retries" =>  2,
            "partials" =>  [
                "type" =>  "Test",
                "name" =>  "email_alert",
                "args" =>  [
                    "task" =>  "Test",
                    "type" =>  "List",
                    "params" =>  [
                        "items" => [],
                    ],
                ],
            ],
        ],
    ];    
    JobManager::createJob($args);    
}

function testCreateDirectoryJob()
{
    $args = [
        "type" =>  "job",
        "name" =>  "directory_report",
        "task" =>  [
            "type" =>  "task",
            "name" =>  "directory_listing",
            "args" =>  [
                "task" =>  "DirectoryList",
                "type" =>  "List",
                "params" =>  [
                    "items" =>  [
                        "/var",
                        "/home",
                        "/bin"
                    ],
                "max_chunk_size" =>  1,
                ]
            ]
        ]
    ];
    JobManager::createJob($args);
}

function testCreateParallelJob()
{
    $args = [
        "type" =>  "job",
        "name" =>  "monthly_report",
        "jobs" =>  [
            [
                "type" =>  "job",
                "name" =>  "db_export",
                "task" =>  [
                    "type" =>  "task",
                    "name" =>  "sql_exporter",
                    "args" =>  [
                        "task" =>  "Test",
                        "type" =>  "DateRange",
                        "params" =>  [
                            "start_date" =>  "2019-11-01 00:00:00",
                            "end_date" =>  "2019-11-30 23:59:59",
                            "max_chunk_size" =>  30
                        ]
                    ]
                ]
            ],
            [
                "type" =>  "job",
                "name" =>  "file_export",
                "task" =>  [
                    "type" =>  "task",
                    "name" =>  "file_exporter",
                    "args" =>  [
                        "task" =>  "DirectoryList",
                        "type" =>  "List",
                        "params" =>  [
                            "items" =>  [
                                "/var",
                                "/home",
                                "/bin"
                            ],
                            "max_chunk_size" =>  3
                        ]
                    ]
                ]
            ]
        ]
    ];
    JobManager::createJob($args);
}

//testCreateJobsTable();
//testCreateWorkersTable();
//testCreateSimpleJob();
//testCreateDirectoryJob();
//testCreateTrigger();
//testCreateMySqlJob();
//testCreateEmployeeReport();
testCreateParallelJob();