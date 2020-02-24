<?php
namespace model\web\Jobs\datasources;

use model\web\Jobs\App\Jobs\Runnables\Job;

/**
 FILENAME:/var/www/adtopy/model/web/objects/Jobs/datasources/FullList.php
 CLASS:FullList
 *
 *
 **/

class FullList 
{
    static $definition = [
        'ROLE' => 'list',
        'DATAFORMAT' => 'Table',
        'PARAMS' => [
            'id_job' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'id_job',
                'TRIGGER_VAR' => 'id_job'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'job_id',
                'TRIGGER_VAR' => 'job_id'
            ],
            'parent' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'parent',
                'TRIGGER_VAR' => 'parent'
            ],
            'name' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'name',
                'TRIGGER_VAR' => 'name'
            ],
            'status' => [
                'TYPE' => 'Array',
                    'ELEMENTS' => [
                        'TYPE' => 'Enum',
                        'VALUES'     => [
                            Job::WAITING,
                            Job::PENDING,
                            Job::RUNNING,
                            Job::FINISHED,
                            Job::FAILED,
                        ],
                    ],
                'TRIGGER_VAR' => 'status',
            ],
            'created_before' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'created_at',
                'TRIGGER_VAR' => 'created_before',
            ],
            'created_after' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'created_at',
                'TRIGGER_VAR' => 'created_after',
            ],
        ],
        'IS_ADMIN' => 0,
        'FIELDS' => [
            'id_job' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'id_job'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'job_id'
            ],
            'parent' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'parent'
            ],
            'name' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'name'
            ],
            'status' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'status'
            ],
            'created_at' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'created_at'
            ],
            'updated_at' => [
                'MODEL' => '\model\web\Jobs',
                'FIELD' => 'updated_at'
            ],
        ],
        'PERMISSIONS' => ['PUBLIC'],
        'SOURCE' => [
            'STORAGE' => [
                'MYSQL' => [
                    'DEFINITION' => [
                        'TABLE' => 'Job',
                        'BASE' => [
                            'id_job',
                            'job_id',
                            'parent',
                            'name',
                            'status',
                            'created_at',
                            'updated_at',
                        ],
                        'CONDITIONS' => [
                            [
                                'FILTER' => [
                                    'F' => 'id_job',
                                    'OP' => '=',
                                    'V' => '[%id_job%]'
                                ],
                                'TRIGGER_VAR' => 'id_job',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'id_job'
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'parent',
                                    'OP' => '=',
                                    'V' => '[%parent%]'
                                ],
                                'TRIGGER_VAR' => 'parent',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'parent'
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'name',
                                    'OP' => '=',
                                    'V' => '[%name%]'
                                ],
                                'TRIGGER_VAR' => 'name',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'name'
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'status',
                                    'OP' => '=',
                                    'V' => '[%status%]'
                                ],
                                'TRIGGER_VAR' => 'status',
                                'DISABLE_IF' => [],
                                'FILTERREF' => 'status',
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'job_id',
                                    'OP' => '=',
                                    'V' => '[%job_id%]'
                                ],
                                'TRIGGER_VAR' => 'job_id',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'job_id'
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'created_at',
                                    'OP' => '<',
                                    'V' => '[%created_before%]'
                                ],
                                'TRIGGER_VAR' => 'created_before',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'created_at'
                            ],
                            [
                                'FILTER' => [
                                    'F' => 'created_at',
                                    'OP' => '>',
                                    'V' => '[%created_after%]'
                                ],
                                'TRIGGER_VAR' => 'created_after',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'created_at'
                            ],
                        ]
                    ]
                ]
            ]
        ]
    ];
}
