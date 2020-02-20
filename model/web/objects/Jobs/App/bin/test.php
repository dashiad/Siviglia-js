<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\JobManager;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Queue;
use model\web\Jobs\App\Jobs\Workers\TestWorker;

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
    $args = [
        'type' => 'job',
        'name' => 'simplest_job',
        'max_retries' => 1,
        'task' =>
        [
            'type' => 'task',
            'name' => 'data_mixer',
            'args' =>
            [
                'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\TestWorker',
                'type' => 'DateRange',
                'params' =>
                [
                    'start_date' => '2019-11-01 00:00:00',
                    'end_date' => '2019-11-30 23:59:59',
                    'max_chunk_size' => 10,
                ],
            ],
        ],
    ];
    return JobManager::createJob($args);
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
                    'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\MySqlWorker',
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
    return JobManager::createJob($args);
}

function testCreateEmployeeReport() 
{
    $args = [
        'type' => 'job',
        'name' => 'employee_report',
        'task' => [
            'type' => 'task',
            'name' => 'employee_sql_query',
            'max_running_children' => 2,
            'args' => [
                'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\EmployeeListWorker',
                'type' => 'None',
                'params' => array()
            ],
        ],
    ];
    return JobManager::createJob($args);
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
                "type" =>  "model\\web\\Jobs\\App\\Jobs\\Workers\\TestWorker",
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
    return JobManager::createJob($args);    
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
                "task" =>  "model\\web\\Jobs\\App\\Jobs\\Workers\\DirectoryListWorker",
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
    return JobManager::createJob($args);
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
                        "task" =>  "model\\web\\Jobs\\App\\Jobs\\Workers\\TestWorker",
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
                        "task" =>  "model\\web\\Jobs\\App\\Jobs\\Workers\\DirectoryListWorker",
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
    return JobManager::createJob($args);
}

function testLocateWorkers()
{
    $packages = \model\reflection\ReflectorFactory::getPackageNames();
    
    $workers = [];
    foreach($packages as $package) {
        try {
            $pkg = new \model\reflection\Package($package, "model");
            $workers[$package] = $pkg->getWorkers();
        } catch (\Throwable $e) {
            continue;
        }
    }
    print_r($workers);
}

function testCreateApiJob()
{
    $className = model\ads\Reporter\workers\SmartXDownloader::class;
    $definition = $className::loadDefinition();
    $definition->max_running_children = 100;
    $definition->max_retries = 1;
    $definition->task = [
        "type" => "task",
        "name" => "task_name",
        "args" => [
            "task" => $className,
            "name" => "smartx_downloader",
            "type" => "List",
            "params" => [
                "max_chunk_size" => 2,
                "items" => [
                    [
                        "call" => "line_item/178983",
                        "params" => [],
                    ], 
                    [
                        "call" => "market_place",
                        "params" => [],
                    ], 
                    [
                        "call" => "device_model",
                        "params" => [],
                    ], 
                    [
                        "call" => "line_item",
                        "params" => [
                            "marketplace_id" => 18,
                            "changed_within" => 7*24*60*60, // modificados en el último día
                            "filter" => "end_date gt ".date("c"), // que no hayan terminado
                        ],
                    ],  
                    [
                        "call" => "campaign",
                        "params" => [
                            "marketplace_id" => 18,
                            "changed_within" => 7*24*60*60, // modificados en el último día
                            "filter" => "end_date gt ".date("c"), // que no hayan terminado
                        ],
		    ],
                ],
            ],
        ]
    ];
    $job = $definition->normalizeToAssociativeArray();
    $job['task']['args']['standalone'] = true;
    //print_r($job);
    return JobManager::createJob($job);
}

function testStopJob($id)
{
    $msg = new SimpleMessage([
        'from'   => 'test',
        'to'     => $id,
        'action' => 'kill',
    ]);
    $queue = Queue::connect('test');
    $queue->publish($msg, $queue->getDefaultChannel(), 'control');
}

function testListJobsDS()
{
    $ds = $ds=\getDataSource('\\model\\web\\Jobs', "FullList");
    $ds->status=3;
    $ds->created_at = "2020-01-01 00:00:00";
    //$ds->created_at = date(DATE_ATOM);
    $jobs = $ds->fetchAll()->getFullData();
    foreach ($jobs as $job) {
        echo $job['job_id']." ---> ".$job['status'].PHP_EOL;
    }
}

function testListWorkersDS()
{
    $ds = $ds=\getDataSource('\\model\\web\\Jobs', "FullList");
    //$ds->status=3;
    $ds->created_at = "2020-01-01 00:00:00";
    //$ds->created_at = date(DATE_ATOM);
    $jobs = $ds->fetchAll()->getFullData();
    $lastJob = array_pop($jobs);
    
    echo $lastJob['job_id']." ---> ".$lastJob['status'].PHP_EOL.PHP_EOL;
    
    $ds = $ds=\getDataSource('\\model\\web\\Jobs\\Worker', "FullList");
    $ds->job_id = $lastJob['job_id'];
    //$ds->alive = 1;
    //$ds->status = 4;
    $workers = $ds->fetchAll();
    $workers = $workers->getFullData();
    foreach ($workers as $worker) {
        echo $worker['worker_id']." ---> ".$worker['status'].PHP_EOL;
        echo $worker['result'].PHP_EOL.PHP_EOL;
    }
}

//testCreateJobsTable();
//testCreateWorkersTable();
//testLocateWorkers();
//$id = testCreateSimpleJob();
//$id = testCreateDirectoryJob();
//$id = testCreateTrigger();
//$id = testCreateMySqlJob();
//$id = testCreateEmployeeReport();
//$id = testCreateParallelJob();
//$id = testCreateApiJob();
//testStopJob($id);
//testListJobsDS();
testListWorkersDS();
