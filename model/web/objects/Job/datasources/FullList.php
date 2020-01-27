<?php
namespace model\web\Job\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Job/datasources/FullList.php
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
                'MODEL' => '\model\web\Job',
                'FIELD' => 'id_job',
                'TRIGGER_VAR' => 'id_job'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'job_id',
                'TRIGGER_VAR' => 'job_id'
            ],
            'parent' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'parent',
                'TRIGGER_VAR' => 'parent'
            ],
            'name' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'name',
                'TRIGGER_VAR' => 'name'
            ],
            'status' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'status',
                'TRIGGER_VAR' => 'status'
            ],
        ],
        'IS_ADMIN' => 0,
        'FIELDS' => [
            'id_job' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'id_job'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'job_id'
            ],
            'parent' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'parent'
            ],
            'name' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'name'
            ],
            'status' => [
                'MODEL' => '\model\web\Job',
                'FIELD' => 'status'
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
                            'parent',
                            'name',
                            'status',
                            'job_id',
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
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'status'
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
                        ]
                    ]
                ]
            ]
        ]
    ];
}