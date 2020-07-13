<?php
namespace model\web\Jobs\Worker;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Job/Definition.php
 CLASS:Definition
 *
 *
 **/
use \lib\model\BaseModelDefinition;
use \model\web\Jobs\Worker;

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
                'MAXLENGTH' => 256,
                'DESCRIPTIVE' => 'true',
                'SHORTLABEL' => 'tag',
                'ISLABEL' => true,
            ],
            'worker_type' => [
                'TYPE' => 'String',
                'LABEL' => 'Tipo de tarea',
                'MINLENGTH' => 8,
                'MAXLENGTH' => 256,
                'DESCRIPTIVE' => 'false',
                'SHORTLABEL' => 'tag',
                'ISLABEL' => true,
            ],
            'job_id' => [
                'DEFAULT' => 'NULL',
                'FIELDS' => ['job_id' => 'job_id'],
                'MODEL' =>  '\\model\\web\\Jobs',
                'LABEL' => 'Job',
                'SHORTLABEL' => 'Job',
                'TYPE' => 'Relationship',
                'MULTIPLICITY' => '1:N',
                'ROLE' => 'HAS_ONE',
                'CARDINALITY' => 1
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
            'items' => [
                'TYPE' => 'Text',
                'LABEL'     => 'Items',
            ],
            'last_completed_item_index' => [
                'TYPE'    => 'Integer',
                'LABEL'   => 'Last completed',
                'DEFAULT' => null,
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
            'alive' => [
                'TYPE'    => 'Boolean',
                'DEFAULT' => true,
            ],
            'descriptor' => [
                'TYPE'    => 'Text',
                'LABEL'   => 'Descriptor',
                'DEFAULT' => null,
            ],
            'result' => [
                'TYPE'    => 'Text',
                //'TYPE' => 'PHPVariable',
                'DEFAULT' => null,
                'LABEL'   => 'Result',
            ],
            'created_at' => [
                'TYPE'       => 'DateTime',
                'LABEL'      => 'Fecha de creación',
                'SHORTLABEL' => 'Creado',
                'ISLABEL'    => 'false',
                'TIMEZONE'   => 'SERVER',
            ],
            'updated_at' => [
                'TYPE'       => 'DateTime',
                'LABEL'      => 'Fecha de modificación',
                'SHORTLABEL' => 'Modificado',
                'ISLABEL'    => 'false',
                'TIMEZONE'   => 'SERVER',
            ],
        ],
        'PERMISSIONS' => [],
        'SOURCE'      => [
            'STORAGE' => [
                'MYSQL' => [
                    'ENGINE'        => 'InnoDb',
                    'CHARACTER SET' => 'utf8',
                    'COLLATE'       => 'utf8_general_ci',
                    'TABLE_OPTIONS' => ['ROW_FORMAT' => 'FIXED']
                ]
            ]
        ]
    ];
}