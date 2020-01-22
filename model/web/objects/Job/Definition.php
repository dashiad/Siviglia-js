<?php
namespace model\web\Job;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Job/Definition.php
 CLASS:Definition
*
*
**/
use \lib\model\BaseModelDefinition;
use \model\web\Job;

class Definition extends BaseModelDefinition
{
	 static $definition = [
               'ROLE' => 'ENTITY',
               'DEFAULT_SERIALIZER' => 'default',
               'DEFAULT_WRITE_SERIALIZER' => 'default',
               'INDEXFIELDS' => ['id_job'],
               'TABLE' => 'Job',
               'LABEL' => 'job',
               'SHORTLABEL' => 'Job',
               'CARDINALITY' => '300',
               'CARDINALITY_TYPE' => 'FIXED',
               'FIELDS' => [
                     'id_job' => [
                           'TYPE' => 'AutoIncrement',
                           'MIN' => 0,
                           'MAX' => 9999999999,
                           'LABEL' => 'id_job',
                           'SHORTLABEL' => 'id_job',
                           'DESCRIPTIVE' => 'true',
                           'ISLABEL' => true
                           ],
                     'job_id' => [
                           'TYPE' => 'String',
                           'LABEL' => 'Job ID',
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
                     'status' =>  [
                         'TYPE'       => 'Enum',
                         'VALUES'     => [
                             Job::WAITING,
                             Job::PENDING,
                             Job::RUNNING,
                             Job::FINISHED,
                             Job::FAILED,
                         ],
                         'DEFAULT'    => Job::WAITING,
                         'LABEL'      => 'Status',
                         'SHORTLABEL' => 'status',
                     ],
                     'parent' => [
                         'TYPE' => 'Relationship',
                         'MODEL' => '\model\web\Job',
                         'ROLE' => 'HAS_MANY',
                         'MULTIPLICITY' => '1:N',
                         'CARDINALITY' => 100,
                         'FIELDS' => ['parent' => 'job_id'],
                     ],
                 ],
        	     'ALIASES'=>[
        	         'workers'=>[
        	             'TYPE'=>'InverseRelation',
        	             'MODEL'=>'\model\web\Worker',
        	             'ROLE'=>'HAS_MANY',
        	             'MULTIPLICITY'=>'1:N',
        	             'CARDINALITY'=>100,
        	             'FIELDS'=>array('job_id'=>'job_id')
        	         ]
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