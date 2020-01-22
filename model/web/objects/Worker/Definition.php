<?php
namespace model\web\Worker;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Job/Definition.php
 CLASS:Definition
 *
 *
 **/
use \lib\model\BaseModelDefinition;
use \model\web\Worker;

class Definition extends BaseModelDefinition
{
    static $definition = [
        'ROLE' => 'ENTITY',
        'DEFAULT_SERIALIZER' => 'default',
        'DEFAULT_WRITE_SERIALIZER' => 'default',
        'INDEXFIELDS' => ['id_worker'],
        'TABLE' => 'Worker',
        'LABEL' => 'worker',
        'SHORTLABEL' => 'Worker',
        'CARDINALITY' => '300',
        'CARDINALITY_TYPE' => 'FIXED',
        'FIELDS' => [
            'id_worker' => [
                'TYPE' => 'AutoIncrement',
                'MIN' => 0,
                'MAX' => 9999999999,
                'LABEL' => 'id_job',
                'SHORTLABEL' => 'id_job',
                'DESCRIPTIVE' => 'true',
                'ISLABEL' => true
            ],
            'worker_id' => [
                'TYPE' => 'String',
                'LABEL' => 'Worker ID',
                'MINLENGTH' => 16,
                'MAXLENGTH' => 64,
                'DESCRIPTIVE' => 'true',
                'SHORTLABEL' => 'tag',
                'ISLABEL' => true,
            ],
            'job_id' => [
                'TYPE' => 'String',
                'LABEL' => 'Job Id',
                'MINLENGTH' => 16,
                'MAXLENGTH' => 64,
                'DESCRIPTIVE' => 'true',
                'SHORTLABEL' => 'tag',
                'ISLABEL' => true,
            ],
            'name' => [
                'TYPE' => 'String',
                'MINLENGTH' => 2,
                'MAXLENGTH' => 64,
                'LABEL' => 'name',
                'SEARCHABLE' => 1,
                'SHORTLABEL' => 'name',
                'DESCRIPTIVE' => 'true',
                'ISLABEL' => true
            ],
            'index' => [
                'TYPE'  => 'Integer',
                'MIN'   => 0,
                'MAX'   => 65535,
                'LABEL' => 'index',
            ],
            'number_of_parts' => [
                'TYPE'  => 'Integer',
                'MIN'   => 1,
                'MAX'   => 65536,
                'LABEL' => 'Number of parts',
            ],
            'status' => [
                'TYPE'       => 'Enum',
                'VALUES'     => [
                    Worker::WAITING,
                    Worker::PENDING,
                    Worker::RUNNING,
                    Worker::FINISHED,
                    Worker::FAILED,
                ],
                'DEFAULT'    => Worker::WAITING,
                'LABEL'      => 'Status',
                'SHORTLABEL' => 'status',
            ],
            'result' => [
                'TYPE' => 'String',
                'DEFAULT' => null,
                'LABEL' => 'Result',
            ],
            'object' => [
                'TYPE' => 'String',
                'LABEL' => 'object',
                'SHORTLABEL' => 'object',
             ],
        ],
        'PERMISSIONS' => [],
        'SOURCE' => [
            'STORAGE' => [
                'MYSQL' => [
                    'ENGINE' => 'InnoDb',
                    'CHARACTER SET' => 'utf8',
                    'COLLATE' => 'utf8_general_ci',
                    'TABLE_OPTIONS' => ['ROW_FORMAT' => 'FIXED']
                ]
            ]
        ]
    ];
}