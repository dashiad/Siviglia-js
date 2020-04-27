<?php
require(__DIR__.'/bootstrap.php');

use model\web\Jobs\App\Jobs\JobManager;
use model\web\Jobs\App\Jobs\Messages\SimpleMessage;
use model\web\Jobs\App\Jobs\Queue;
use model\web\Jobs\App\Jobs\Workers\TestWorker;
use model\web\Jobs\App\Jobs\Runnables\Job;
use model\ads\Reporter\workers\SmartXDownloader;
use model\ads\Reporter\workers\ComscoreReportCreator\ComscoreApi;
use lib\php\ParametrizableString;


const SIMPLE_JOB = [
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

const MYSQL_JOB = [
    'type' => 'job',
    'name' => 'daily_report',
    'task' => [
        'type' => 'task',
        'name' => 'data_mixer',
        'max_running_children' => 2,
        'args' => [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\MySqlWorker',
            'type' => 'DateRange',
            'params' => [
                'start_date' => '2019-11-01 00:00:00',
                'end_date' => '2019-11-30 23:59:59',
                'max_chunk_size' => 30,
            ],
        ],
    ],
];

const EMPLOYEE_JOB = [
    'type' => 'job',
    'name' => 'employee_report',
    'task' => [
        'type' => 'task',
        'name' => 'employee_sql_query',
        'max_running_children' => 2,
        'args' => [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\EmployeeListWorker',
            'type' => 'None',
            'params' => []
        ],
    ],
];

const DIRECTORY_JOB = [
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

const PARALLEL_JOB = [
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

const API_TASK = [
    "type" => "task",
    "name" => "task_name",
    "args" => [
        "task" => null,
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
                        "changed_within" => 1*24*60*60, // modificados en el último día
                        "filter" => "end_date gt 2020-02-19 00:00:00", // que no hayan terminado
                    ],
                ],
                [
                    "call" => "campaign",
                    "params" => [
                        "marketplace_id" => 18,
                        "changed_within" => 1*24*60*60, // modificados en el último día
                        "filter" => "end_date gt  2020-02-19 00:00:00", // que no hayan terminado
                    ],
                ],
            ],
        ],
    ]
];

const TRIGGER = [
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

const COMSCORE_TASK = [
    "type" => "task",
    "name" => "comscore_report_generator",
    "args" => [
        "task"   => null,
        "name"   => "comscore_report_generator",
        "type"   => "DateRange", // procesa el rango de fechas día por día
        //"type" => "None",  // procesa el rango de fechas como un agregado
        "params" => [
            "timeout"        => "180",
            "region"         => "spain", // "spain" o "latam" (posible Enum) 
            "type"           => "Demographic",
            "view_by_type"   => "Total",
            "start_date"     => "2020-01-01",
            "end_date"       => "2020-02-25",
            "max_chunk_size" => 10,
            "campaigns"      => ["DIR_29664"],
        ],
    ],
];


const COMSCORE_API_CALL_LIST = [
    'region'   => 'spain',
    'url'      => 'clients/[%client_id%]/jobs/processing',
    'method'   => 'GET',
    'headers'  => [
        'Accept'       => 'application/json',
        //'Content-type' => 'application/json',
    ],
];

const COMSCORE_API_POST = [
    'REGION'    => 'spain',
    'URL'       => 'clients/[%client_id%]/jobs/reporting/Demographic',
    'METHOD'    => 'POST',
    'HEADERS'   => [
        'Accept'       => 'application/json',
        'Content-type' => 'application/json',
    ],
    'PARAMS'    => [
        "responseMediaType" => "text/csv",
        "includeMobile"     => true,
        "campaignIds"       => ["DIR_29664"],
        "clientId"          => "[%client_id%]",
        "populationId"      => 724,
        "viewByType"        => "Total",
        "startDate"         => "01-03-2020",
        "endDate"           => "03-03-2020"
    ],
];

const COMSCORE_API_CALL_STATUS = [
    'REGION'   => 'spain',
    'URL'      => 'clients/[%client_id%]/jobs/reporting/10386096',
    'METHOD'   => 'GET',
    'HEADERS'  => [
        'Accept' => 'application/json',
    ],
];

const COMSCORE_API_CALL_RESULT = [
    'REGION'   => 'spain',
    'URL'      => 'clients/[%client_id%]/jobs/reporting/10363900/result',
    'METHOD'   => 'GET',
    'HEADERS'  => [
        'Accept' => 'text/csv',
    ],
];

const COMSCORE_DS_FREQUENCY = [
    'type' => 'job',
    'name' => 'comscore_frequency',
    'max_retries' => 1,
    'task' =>
    [
        'type' => 'task',
        'name' => 'comscore_frequency_worker',
        'args' =>
        [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\DataSourceWorker',
            'type' => 'None',
            'params' =>
            [
                'model' => 'model\\ads\\Comscore',
                'datasource' => 'FrequencyReport',
                'start_date' => '2019-06-07',
                'end_date' => '2019-07-07',
                'campaigns' => ["DIR_25606", "DFP_2552176784"],
            ],
        ],
    ],
];

const COMSCORE_DS_DEMOGRAPHICS = [
    'type' => 'job',
    'name' => 'comscore_demographics',
    'max_retries' => 1,
    'task' =>
    [
        'type' => 'task',
        'name' => 'comscore_demographics_worker',
        'args' =>
        [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\DataSourceWorker',
            'type' => 'None',
            'params' =>
            [
                'model' => 'model\\ads\\Comscore',
                'datasource' => 'DemographicReport',
                'start_date' => '2019-06-07',
                'end_date' => '2019-07-07',
                'campaigns' => ["DIR_25606", "DFP_2552176784"],
            ],
        ],
    ],
];


const SITE_DS_JOB = [
    'type' => 'job',
    'name' => 'sites',
    'max_retries' => 1,
    'task' =>
    [
        'type' => 'task',
        'name' => 'sites_worker',
        'args' =>
        [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\DataSourceWorker',
            'type' => 'None',
            'params' =>
            [
                'model' => 'model\\web\\Site',
                'datasource' => 'FullList',
            ],
        ],
    ],
];

const PAGE_DS_JOB = [
    'type' => 'job',
    'name' => 'sites',
    'max_retries' => 1,
    'task' =>
    [
        'type' => 'task',
        'name' => 'pages_worker',
        'args' =>
        [
            'task' => 'model\\web\\Jobs\\App\\Jobs\\Workers\\DataSourceWorker',
            'type' => 'None',
            'params' =>
            [
                'model' => 'model\\web\\Page',
                'datasource' => 'FullList',
            ],
        ],
    ],
];

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
    $args = SIMPLE_JOB;
    return JobManager::createJob($args);
}

function testCreateMySqlJob()
{
    $args = MYSQL_JOB;
    return JobManager::createJob($args);
}

function testCreateEmployeeReport() 
{
    $args = EMPLOYEE_JOB;
    return JobManager::createJob($args);
}

function testCreateTrigger()
{
    $args = TRIGGER;
    return JobManager::createJob($args);    
}

function testCreateDirectoryJob()
{
    $args = DIRECTORY_JOB;
    return JobManager::createJob($args);
}

function testCreateParallelJob()
{
    $args = PARALLEL_JOB;
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
    $job = $definition->normalizeToAssociativeArray();
    $job['task'] = API_TASK;
    $job['task']['args']['task'] = $className;
    return JobManager::createJob($job);
}

function testCreateComscoreJob()
{
    $className = \model\ads\Reporter\workers\ComscoreReportCreator::class;
    $definition = $className::loadDefinition();
    $definition->max_running_children = 100;
    $definition->max_retries = 1;
    $job = $definition->normalizeToAssociativeArray();
    $job['task'] = COMSCORE_TASK;
    $job['task']['args']['task'] = $className;
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
    $ds = \getDataSource('\\model\\web\\Jobs', "FullList");
    $ds->status = [
        Job::RUNNING,
        Job::FINISHED,
        Job::FAILED,
    ];
    $ds->created_after = "2020-01-21 00:00:00";
    $ds->created_before = "2020-02-22 00:00:00";
    
    $jobs = $ds->fetchAll();
    for ($i=0;$i<$jobs->count();$i++) {
        $job = $jobs[$i];
        echo $job->job_id." ---> ".$job->status.PHP_EOL;
    }
}

function testListWorkersDS($jobId=null)
{
    
    if (is_null($jobId)) {
        $ds=\getDataSource('\\model\\web\\Jobs', "FullList");
        $ds->created_after = "2020-02-20 12:00:00";
        $jobs = $ds->fetchAll()->getFullData();
        $jobId = array_pop($jobs)['job_id'];
    }
    
    $ds=\getDataSource('\\model\\web\\Jobs\\Worker', "FullList");
    $ds->job_id = $jobId;
    $workers = $ds->fetchAll();
    for ($i=0;$i<$workers->count();$i++) {
        $worker = $workers[$i];
        echo $worker->worker_id." ---> ".$worker->status.PHP_EOL;
        echo $worker->result.PHP_EOL.PHP_EOL;
    }
}

function testAction($args = SIMPLE_JOB)
{
    //global $globalUser;
    $globalUser = "web";
    
    $act=\lib\action\Action::getAction('\model\web\Jobs','AddAction');    
    $actionResult=new \lib\action\ActionResult();
    $instance=$act->getParametersInstance();
    
    //$instance->job_id = "THIS_IS_A_FORCED_JOB_ID";
    $instance->name = "test";
    $instance->descriptor = json_encode($args);
    
    $act->process($instance, $actionResult, $globalUser);
    if ($actionResult->isOk()) {
        return $actionResult->getModel();
    } else {
        foreach ($actionResult->getFieldErrors() as $error) {
            print_r($error);
        }
        return false;
    }
}


function testCreateDsJob($args = COMSCORE_DS_FREQUENCY)
{
    return JobManager::createJob($args);
}

//testCreateJobsTable();
//testCreateWorkersTable();
//testLocateWorkers();
//testCreateTrigger();
//$jobs[] = testCreateSimpleJob();
//$jobs[] = testCreateDirectoryJob();
//$jobs[] = testCreateMySqlJob();
//$jobs[] = testCreateEmployeeReport();
//$jobs[] = testCreateParallelJob();
//$jobs[] = testCreateApiJob();
//$jobs[] = testCreateComscoreJob();
//testListJobsDS();
//testListWorkersDS();
//$jobs[] = testAction()->job_id;
//testStopJob($jobs[rand(0, count($jobs)-1)]);
/*foreach($jobs as $job) {
    echo $job.PHP_EOL;
    testListWorkersDS($job);
}*/
//testCreateDsJob(PAGE_DS_JOB);
testCreateDsJob(COMSCORE_DS_DEMOGRAPHICS);
testCreateDsJob(COMSCORE_DS_FREQUENCY);
