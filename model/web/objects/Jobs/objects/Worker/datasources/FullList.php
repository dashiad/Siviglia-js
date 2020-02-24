<?php
namespace model\web\Jobs\Worker\datasources;

/**
 FILENAME:/var/www/adtopy/model/web/objects/Jobs/objects/Worker/datasources/FullList.php
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
            'id_worker' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'id_worker',
                'TRIGGER_VAR' => 'id_worker'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'job_id',
                'TRIGGER_VAR' => 'job_id'
            ],
            'index' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'index',
                'TRIGGER_VAR' => 'index'
            ],
            'worker_id' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'worker_id',
                'TRIGGER_VAR' => 'worker_id'
            ],
            'name' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'name',
                'TRIGGER_VAR' => 'name'
            ],
            'status' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'status',
                'TRIGGER_VAR' => 'status'
            ],
            'number_of_parts' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'number_of_parts',
                'TRIGGER_VAR' => 'number_of_parts'
            ],
            'alive' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'alive',
                'TRIGGER_VAR' => 'alive'
            ],
            'result' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'result',
                'TRIGGER_VAR' => 'result'
            ],
        ],
        'IS_ADMIN' => 0,
        'FIELDS' => [
            'id_worker' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'id_worker'
            ],
            'job_id' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'job_id'
            ],
            'index' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'index'
            ],
            'worker_id' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'worker_id'
            ],
            'name' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'name'
            ],
            'status' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'status'
            ],
            'number_of_parts' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'number_of_parts'
            ],
            'alive' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'alive'
            ],
            'result' => [
                'MODEL' => '\model\web\Jobs\Worker',
                'FIELD' => 'result'
            ],
        ],
        'PERMISSIONS' => ['PUBLIC'],
        'SOURCE' => [
            'STORAGE' => [
                'MYSQL' => [
                    'DEFINITION' => [
                        'TABLE' => 'Worker',
                        'BASE' => [
                            'id_worker',
                            'worker_id',
                            'name',
                            'status',
                            'job_id',
                            '`index`',
                            'number_of_parts',
                            'items',
                            'last_completed_item_index',
                            'descriptor',
                            'result',
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
                                    'F' => 'worker_id',
                                    'OP' => '=',
                                    'V' => '[%worker_id%]'
                                ],
                                'TRIGGER_VAR' => 'worker_id',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'worker_id'
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
                                    'F' => 'alive',
                                    'OP' => '=',
                                    'V' => '[%alive%]'
                                ],
                                'TRIGGER_VAR' => 'alive',
                                'DISABLE_IF' => '0',
                                'FILTERREF' => 'alive'
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
                                    'OP' => '>',
                                    'V' => '[%created_at%]'
                                ],
                                'TRIGGER_VAR' => 'created_at',
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